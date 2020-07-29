<?php

namespace App\Http\Controllers\Frontend;

use Session;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function show(){
        $orders = Order::with('product')->where('user_id', Auth::user()->id)->orderBy('ordered_at','desc')->get();
        return view('frontend.order.show',compact('orders'));
    }

    public function orderdestroy($id){
        $cancelation_info = ['canceled_by' => 'self','canceled_at' => now() ]; 
        $order = Order::where('user_id',Auth::user()->id)->findOrFail($id);
        $order->order_status = 2;
        $order->shipping_status = 2;
        $order->cancelation_info =  $cancelation_info;
        $order->save();
        $order->product()->detach();
        Session::flash('cancel_confirmed','Order Cancelled Successfully');
        return redirect()->route('orders.show');
    }


    public function confirmation($id){
        $orderinfo = Order::with('user','product')->findOrFail($id);
        return view('frontend.confirmation.index',compact('orderinfo'));
    }
}
