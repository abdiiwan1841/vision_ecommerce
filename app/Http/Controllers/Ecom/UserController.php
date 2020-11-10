<?php

namespace App\Http\Controllers\Ecom;

use Session;
use App\User;
use App\Section;
use App\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:Ecommerce Section');
        
    }
    public function index(){
        $customers = User::where('user_type','ecom')->get();
        return view('ecom.user.index',compact('customers'));
    }

    public function create()
    {
       
        $divisions = Division::all();
        $sections = Section::where('module','ecommerce')->get();
        return view('ecom.user.create',compact('divisions','sections'));
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
            'phone' => 'required',
            'division' => 'required',
            'address' => 'required|max:300',
        ]);

        $user = new User;
        $user->name = $request->name;
        if($request->has('email')){
        $user->custom_email = $request->email;
        }
        $user->phone = $request->phone;
        $user->division_id = $request->division;
        $user->address = $request->address;
        $user->section_id = 4;
        $user->user_type = 'ecom';
        $user->status = 0;
        $user->save();
        Toastr::success('Ecommerce Customer created successfully');
        return redirect(route('ecomcustomer.index'));
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
        $customer = User::findOrFail($id);
        if($customer->section_id == 3){
            Toastr::error('Online Customer Can Not Editable', 'error');
            return redirect()->back();
        }else{
            $divisions = Division::all();
            $sections = Section::where('module','ecommerce')->get();
            return view('ecom.user.edit',compact('divisions','customer','sections'));
        }

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
            'phone' => 'required',
            'division' => 'required',
            'address' => 'required|max:300',
        ]);
        
        $user = User::findOrFail($id);
        $user->name = $request->name;
        if($request->has('email')){
            $user->custom_email = $request->email;
        }
        $user->phone = $request->phone;
        $user->division_id = $request->division;
        $user->address = $request->address;
        $user->section_id = 4;
        $user->user_type = 'ecom';
        $user->status = 0;
        $user->save();
        Toastr::success('Ecommerce Customer Updated successfully');
        return redirect(route('ecomcustomer.index'));
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

    // public function export(Request $request){
    //     $general_opt = GeneralOption::first();
    //     $general_opt_value = json_decode($general_opt->options, true);
    //     $customers = User::where('user_type','pos')->get();
    //     $pdf = PDF::loadView('pos.user.export',compact('customers','general_opt_value'));
    //     return $pdf->download('Customer Export'.time().'.pdf');
    // }
    
}
