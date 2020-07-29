<?php

namespace App\Http\Controllers\Frontend;

use App\Charge;
use App\Headertop;
use Illuminate\Http\Request;
use Harimayco\Menu\Facades\Menu;
use App\Http\Controllers\Controller;

class CartpageController extends Controller
{
    public function index(){

        $charges = Charge::first();
        return view('frontend.cart.index',compact('charges'));
    }

    public function charge(){
        return Charge::first();
    }

}
