<?php

namespace App\Http\Controllers\Pos;
use PDF;
use Session;
use App\User;
use App\Product;
use App\Section;
use App\District;
use App\Division;
use App\GeneralOption;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:Inventory Customers');
        $this->middleware('permission:Inventory Customer Edit')->only('edit','update');
    }

    
    public function index()
    {
        $customers = User::where('user_type','pos')->get();
        return view('pos.user.index',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $divisions = Division::all();
        $sections = Section::where('module','inventory')->get();
        return view('pos.user.create',compact('divisions','sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $this->validate($request,[
            'name' =>  'required|string|max:30',
            'proprietor' =>  'max:30',
            // 'inventory_email' => 'unique:users',
            'phone' => 'required',
            'division' => 'required',
            'section' => 'required|integer',
            'address' => 'required|max:500',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->proprietor = $request->proprietor;
        $user->inventory_email = $request->inventory_email;
        $user->phone = $request->phone;
        $user->division_id = $request->division;
        $user->address = $request->address;
        $user->section_id = $request->section;
        $user->password = NULL;
        $user->user_type = 'pos';
        if($request->has('company')){
         $user->company = $request->company;
        }
        $user->status = 0;
        $user->save();
        Session::flash('success','Customer created successfully');
        return redirect(route('customers.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Product::all();
        $customer = User::findOrFail($id);
        $divisions = Division::all();
        $sections = Section::where('module','inventory')->get();
        $pricedata =  $customer->pricedata;
        return view('pos.user.edit',compact('divisions','customer','products','pricedata','sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' =>  'required|string|max:30',
            'proprietor' =>  'max:30',
            // 'inventory_email' => 'unique:users,inventory_email,'.$id,
            //'phone' => 'integer',
            'division' => 'required',
            'section' => 'required',
            'address' => 'required|max:500',
        ]);
        
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->proprietor = $request->proprietor;
        $user->inventory_email = $request->inventory_email;
        $user->phone = $request->phone;
        $user->division_id = $request->division;
        $user->section_id = $request->section;
        $user->address = $request->address;
        if(strlen($request->pricedata) < 6){
            $user->pricedata = null;
        }else{
            $user->pricedata = $request->pricedata;
        }
        $user->user_type = 'pos';
        if($request->has('company')){
         $user->company = $request->company;
        }
        $user->status = 0;
        $user->save();
        Session::flash('success','Customer Updated successfully');
        return redirect(route('customers.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return false;
    }

    public function export(Request $request){
        $general_opt = GeneralOption::first();
        $general_opt_value = json_decode($general_opt->options, true);
        $customers = User::where('user_type','pos')->get();
        $pdf = PDF::loadView('pos.user.export',compact('customers','general_opt_value'));
        return $pdf->download('Customer Export'.time().'.pdf');
    }
}
