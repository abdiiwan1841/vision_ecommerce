<?php

namespace App\Http\Controllers\Ecom;

use App\Http\Controllers\Controller;
use App\Size;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Session;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = Size::where('type','ecom')->get();
        return view('sizes.index',compact('sizes'));
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
        $this->validate($request,[
            'name' => 'required|max:30'
        ]);

        $size = new Size;
        $size->name = $request->name;
        $size->type = 'ecom';
        $size->save();
        Toastr::success('Size Saved Successfully', 'success');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function edit(Size $size)
    {
        return $size;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Size $size)
    {
        $this->validate($request,[
            'edit_name' => 'required|max:30'
        ]);

        $size->name = $request->edit_name;
        $size->type = 'ecom';
        $size->save();
        Toastr::success('Size Saved Successfully', 'success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy(Size $size)
    {
        $size->deleted_at = now();
        $size->save();
        Session::flash('delete_success','Size Deleted Succesfully');
        return redirect()->back();

    }
}
