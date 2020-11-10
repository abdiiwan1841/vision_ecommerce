<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:Ecommerce Section');
    }
    public function index(){
        return view('admin.menus.index');
    }
}
