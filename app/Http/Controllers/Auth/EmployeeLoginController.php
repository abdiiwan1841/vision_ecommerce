<?php

namespace App\Http\Controllers\Auth;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EmployeeloginRequest;

class EmployeeLoginController extends Controller
{
    public function EmployeeLogin(){
        if (Auth::guard('employee')->check()) {
            return redirect(route('employee.dashboard'));
        }else{
            return view('employee.auth.login');
        }
    }


    public function EmployeeLoginSubmit(EmployeeloginRequest $request){
        
        $credentials = ['phone' => $request->phone, 'password' => $request->password];
        $remember = $request->remember;
        if (Auth::guard('employee')->attempt($credentials,$remember)) {
           return redirect(route('employee.dashboard'));
        }else{
            Session()->flash('error', 'Invalid Phone/Password Conbination');
            return redirect()->back()->withInput($request->only('phone', 'remember'));
        }
    }

    
    public function employeeLogout(){
        Auth::guard('employee')->logout();
        Session::regenerate();
        return redirect(route('employee.login'));
    }
}
