<?php

namespace App\Http\Controllers\Pos;

use Session;
use App\User;
use App\District;
use App\Division;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = User::with('area')->where('user_type','pos')->get();
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
        return view('pos.user.create',compact('divisions'));
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
            //'phone' => 'integer',
            'division' => 'required',
            'district' => 'required',
            'area' => 'required',
            'address' => 'required|max:500',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->proprietor = $request->proprietor;
        $user->inventory_email = $request->inventory_email;
        $user->phone = $request->phone;
        $user->division_id = $request->division;
        $user->district_id = $request->district;
        $user->area_id = $request->area;
        $user->address = $request->address;
        $user->password = Hash::make(123456);
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
        $pricedata =  $customer->pricedata;
        return view('pos.user.edit',compact('divisions','customer','products','pricedata'));
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
            'district' => 'required',
            'area' => 'required',
            'address' => 'required|max:500',
        ]);
        
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->proprietor = $request->proprietor;
        $user->inventory_email = $request->inventory_email;
        $user->phone = $request->phone;
        $user->division_id = $request->division;
        $user->district_id = $request->district;
        $user->area_id = $request->area;
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
        $user = User::findOrFail($id);
        $user->deleted_at = now();
        $user->save();
        Session::flash('success','User Deleted successfully');
        return redirect(route('customers.index'));
    }
}
