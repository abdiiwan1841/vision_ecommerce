<?php

namespace App\Http\Controllers;

use App\District;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class DistrictController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }
    
    public function index()
    {
        $districts = District::all();
        return view('district.index',compact('districts'));
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
            'name' => 'required|unique:districts',

        ]);

        $district = new District;
        $district->name = $request->name;
        $district->save();
        Toastr::success('District Save Successfully', 'success');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\District  $district
     * @return \Illuminate\Http\Response
     */
    public function show(District $district)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\District  $district
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
     $district = District::findOrFail($id);

     return $district;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\District  $district
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, District $district)
    {
        
        $this->validate($request,[
            'name' => 'required|unique:districts,name,'.$district->id,

        ]);
        $district->name = $request->name;
        $district->save();
        Toastr::success('District Updated Successfully', 'success');
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\District  $district
     * @return \Illuminate\Http\Response
     */
    public function destroy(District $district)
    {
        //
    }
}
