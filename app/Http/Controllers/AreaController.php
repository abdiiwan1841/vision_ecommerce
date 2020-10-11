<?php

namespace App\Http\Controllers;

use App\Area;
use App\District;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class AreaController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }

    
    public function index()
    {   $districts = District::all();
        $areas = Area::with('district')->get();
        return view('area.index',compact('areas','districts'));
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
            'district' => 'required',
            'name' => 'required|unique:areas',

        ]);
        $area = new Area;
        $area->district_id = $request->district;
        $area->name = $request->name;
        $area->save();
        Toastr::success('District Save Successfully', 'success');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Area::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
          
        $this->validate($request,[
            'district' => 'required',
            'name' => 'required|unique:areas,name,'.$area->id,

        ]);
        $area->district_id = $request->district;
        $area->name = $request->name;
        $area->save();
        Toastr::success('Area Updated Successfully', 'success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy(Area $area)
    {
        //
    }
}
