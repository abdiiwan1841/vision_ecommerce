<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\adminLoginRequest;

class AdminLoginController extends Controller
{
    public function adminLogin(){
        if (Auth::guard('admin')->check()) {
            return redirect(route('admin.inventorydashboard'));
        }else{
            return view('admin.auth.login');
        }
        
    }


    function validateinput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    public function adminLoginSubmit(adminLoginRequest $request){

        $remember = $request->remember;
        $adminuserinfo = $this->validateinput($request->adminname);

        if($admin = Admin::where('adminname',$adminuserinfo)->first()){
        if($admin->status == 0){
                Session()->flash('error', 'user currently disabled contact administrator for activation');
                return redirect()->back()->withInput($request->only('adminname', 'remember'));
        }else{
                if(Auth::guard('admin')->attempt(['adminname' => $adminuserinfo, 'password' => $request->password],$remember)){
                    return redirect(route('admin.inventorydashboard'));
                }else{
                    Session()->flash('error', 'Invalid Password');
                    return redirect()->back()->withInput($request->only('adminname', 'remember'));
                }
        }
        }elseif( $admin = Admin::where('phone',$adminuserinfo)->first()){
            if($admin->status == 0){
                Session()->flash('error', 'user currently disabled contact administrator for activation');
                return redirect()->back()->withInput($request->only('adminname', 'remember'));
        }else{
                if(Auth::guard('admin')->attempt(['phone'=> $adminuserinfo, 'password' => $request->password],$remember)){
                    return redirect(route('admin.inventorydashboard'));
                }else{
                    Session()->flash('error', 'Invalid Password');
                    return redirect()->back()->withInput($request->only('adminname', 'remember'));
                }
        }
        }elseif($admin = Admin::where('email',$adminuserinfo)->first()){
            if($admin->status == 0){
                Session()->flash('error', 'user currently disabled contact administrator for activation');
                return redirect()->back()->withInput($request->only('adminname', 'remember'));
        }else{
                if(Auth::guard('admin')->attempt(['email'=> $adminuserinfo, 'password' => $request->password],$remember)){
                    return redirect(route('admin.inventorydashboard'));
                }else{
                    Session()->flash('error', 'Invalid Password');
                    return redirect()->back()->withInput($request->only('adminname', 'remember'));
                }
        }
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
