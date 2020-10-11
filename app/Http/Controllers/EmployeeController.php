<?php

namespace App\Http\Controllers;

use App\Employee;
use App\EmployeeType;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;


class EmployeeController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }

    
    public function index()
    {
        $employees = Employee::all(); 
        return view('employee.index',compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $emp_types = EmployeeType::all(); 
        return view('employee.create',compact('emp_types'));
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
            'name' => 'required|unique:employees',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|unique:employees',
            'address' => 'required',
            'joining_date' => 'required',
            'salary' => 'required|integer',
            'employee_type_id' => 'required|integer',
        ]);

        $employee = new Employee;
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->address = $request->address;
        $employee->joining_date = $request->joining_date;
        $employee->salary = $request->salary;
        if($request->has('nid')) :
        $employee->nid = $request->nid;
        endif;
        $employee->employee_type_id = $request->employee_type_id;
        $employee->save();
        Toastr::success('Employee  Created Successfully', 'success');
        return redirect()->route('employee.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return  view('employee.show',compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        $emp_types = EmployeeType::all(); 
        return view('employee.edit',compact('employee','emp_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $this->validate($request,[
            'name' => 'required|unique:employees,name,'.$employee->id,
            'email' => 'required|email|unique:employees,email,'.$employee->id,
            'phone' => 'required|unique:employees,phone,'.$employee->id,
            'address' => 'required',
            'joining_date' => 'required',
            'salary' => 'required|integer',
            'employee_type_id' => 'required|integer',
        ]);

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->address = $request->address;
        $employee->joining_date = $request->joining_date;
        $employee->salary = $request->salary;
        if($request->has('nid')) :
        $employee->nid = $request->nid;
        endif;
        $employee->employee_type_id = $request->employee_type_id;
        $employee->save();
        Toastr::success('Employee  Updated Successfully', 'success');
        return redirect()->route('employee.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
