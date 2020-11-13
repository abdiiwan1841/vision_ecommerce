<?php

namespace App\Http\Controllers;

use App\EmployeeType;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class EmployeeTypeController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:Employee Section');
        $this->middleware('permission:Employee Edit')->only('edit','update');
    }
    
    public function index()
    {
        $emp_types = EmployeeType::all(); 
        return view('employee_type.index',compact('emp_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employee_type.create');
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
            'emp_type' => 'required|unique:App\EmployeeType,name',
        ]);
        $emp_types = new EmployeeType; 
        $emp_types->name = $request->emp_type;
        $emp_types->save();
        Toastr::success('Employee Type Created Successfully', 'success');
        return redirect()->route('emp_type.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmployeeType  $employeeType
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeType $employeeType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmployeeType  $employeeType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $emp_type = EmployeeType::findOrFail($id);
        return view('employee_type.edit',compact('emp_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmployeeType  $employeeType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'emp_type' => 'required|unique:App\EmployeeType,name,'.$id,
        ]);
        $emp_types = EmployeeType::findOrFail($id); 
        $emp_types->name = $request->emp_type;
        $emp_types->save();
        Toastr::success('Employee Type Updated Successfully', 'success');
        return redirect()->route('emp_type.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmployeeType  $employeeType
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeType $employeeType)
    {
        //
    }
}
