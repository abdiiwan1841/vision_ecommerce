<?php

namespace App\Http\Controllers\Ecom;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(){
        $customers = User::with('area')->where('user_type','ecom')->get();
        return view('ecom.user.index',compact('customers'));
    }
    
}
