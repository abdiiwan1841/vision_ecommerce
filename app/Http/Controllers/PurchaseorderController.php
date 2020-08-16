<?php

namespace App\Http\Controllers;

use App\Purchaseorder;
use Illuminate\Http\Request;

class PurchaseorderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchaseorder  $purchaseorder
     * @return \Illuminate\Http\Response
     */
    public function show(Purchaseorder $purchaseorder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchaseorder  $purchaseorder
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchaseorder $purchaseorder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchaseorder  $purchaseorder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchaseorder $purchaseorder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchaseorder  $purchaseorder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchaseorder $purchaseorder)
    {
        //
    }
}
