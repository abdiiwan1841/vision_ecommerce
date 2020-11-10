<?php

namespace App\Http\Controllers;

use App\Deliveryinfo;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class DeliveryinfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:Ecommerce Section');
    }

    
    public function index()
    {
        $deliveryinfo = Deliveryinfo::first();
        return view('delivery.index',compact('deliveryinfo'));
    }



    public function edit(Deliveryinfo $deliveryinfo)
    {
        return $deliveryinfo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Deliveryinfo  $deliveryinfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deliveryinfo $deliveryinfo)
    {
        $this->validate($request,[
            'edit_delay' => 'required|numeric'
        ]);
        $deliveryinfo->delay = $request->edit_delay;
        $deliveryinfo->save();
        Toastr::success('Delivery Information Updated Successfully', 'success');
        return redirect()->back();
    }


}
