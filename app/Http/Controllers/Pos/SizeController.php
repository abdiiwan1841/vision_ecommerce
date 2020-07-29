<?php

namespace App\Http\Controllers\Pos;

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
        $sizes = Size::where('type','pos')->get();
        return view('pos.sizes.index',compact('sizes'));
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
        $size->type = 'pos';
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
    public function edit($id)
    {
        return Size::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'edit_name' => 'required|max:30'
        ]);
        $size = Size::findOrFail($id);

        $size->name = $request->edit_name;
        $size->type = 'pos';
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
    public function destroy($id)
    {
        $size = Size::findOrFail($id);
        $size->deleted_at = now();
        $size->save();
        Session::flash('delete_success','Size Deleted Succesfully');
        return redirect()->back();

    }
}
