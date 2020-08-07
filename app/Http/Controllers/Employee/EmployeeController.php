<?php

namespace App\Http\Controllers\Employee;

use App\Order;
use App\Returnproduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    public function dashboard(){
        $today = now()->toDateString();
        $todays_order = Order::whereBetween('ordered_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('ordered_at', 'desc')->get();

        $todays_ecom_cash = Order::where('payment_status',1)->whereBetween('paymented_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('paymented_at', 'desc')->get();

        $todays_ecom_returns = Returnproduct::where('type','ecom')->whereBetween('returned_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('returned_at', 'desc')->get();

        $pending_orders = Order::where('order_status',0)->get();
        $pending_shipping = Order::where('order_status',1)->where('shipping_status',0)->get();
        return view('employee.dashboard.index',compact('pending_orders','todays_order','todays_ecom_cash','todays_ecom_returns','pending_shipping'));
    }
}
