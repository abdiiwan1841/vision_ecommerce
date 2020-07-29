<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;

class CartSessionController extends Controller
{
    
    public function PushCartSession(Request $request){
        //$ShoppingCart = ['ShoppingCart' => $request->ShoppingCart ];
        $request->session()->put('ShoppingCart', $request->ShoppingCart);
        return Session::get('ShoppingCart');
    }
    public function getCartSession(){
        $value = Session::get('ShoppingCart');
        return $value;
    }
}
