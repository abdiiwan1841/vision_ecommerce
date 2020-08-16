<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\adminLoginRequest;
use Session;

class AdminLoginController extends Controller
{
    public function adminLogin(){
        if (Auth::guard('admin')->check()) {
            return redirect(route('admin.dashboard'));
        }else{
            return view('admin.auth.login');
        }
        
    }
    public function adminLoginSubmit(adminLoginRequest $request){
    
        $credentials1 = ['adminname' => $request->adminname, 'password' => $request->password];
        $credentials2 = ['phone' => $request->adminname, 'password' => $request->password];
        $remember = $request->remember;
        if (Auth::guard('admin')->attempt($credentials1,$remember)) {
            return redirect(route('admin.dashboard'));
        }elseif(Auth::guard('admin')->attempt($credentials2,$remember)){
            return redirect(route('admin.dashboard'));
        }else{
            Session()->flash('error', 'Invalid User/Password Conbination');
            return redirect()->back()->withInput($request->only('adminname', 'remember'));
        }
        
    }

    public function adminLogout(){
        Auth::guard('admin')->logout();
        Session::regenerate();
        return redirect(route('admin.login'));
    }

    

}
