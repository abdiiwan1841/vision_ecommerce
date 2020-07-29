<?php

namespace App\Http\Controllers;

use Session;
use App\Damage;
use App\Product;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DamageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $damages = Damage::with('product')->orderBy('damaged_at','desc')->get();
        return view('damage.index',compact('damages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::get();
        return view('damage.create',compact('products'));
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
            'damage_date' => 'required|date',
            'product' => 'required',
        ]);

        $products = json_decode($request->product);


        $damage = new Damage;
        $damage->damaged_at = $request->damage_date." ".Carbon::now()->toTimeString();
        $damage->reason = $request->reason;
        $damage->save();

        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['damage_id' =>$damage->id, 'product_id' => $product->id,'qty' => $product->count,'damaged_at' => $request->damage_date." ".Carbon::now()->toTimeString() ];   
        }
        $damage->product()->attach($product_info);
        Toastr::success('Damage Stored Successfully', 'success');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Damage  $damage
     * @return \Illuminate\Http\Response
     */
    public function show(Damage $damage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Damage  $damage
     * @return \Illuminate\Http\Response
     */
    public function edit(Damage $damage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Damage  $damage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Damage $damage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Damage  $damage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Damage $damage)
    {
        $damage->deleted_at = now();
        $damage->save();
        $damage->product()->detach();
        Toastr::success('Damage Cancelled Successfully', 'success');
        return redirect()->route('damages.index');
    }
}
