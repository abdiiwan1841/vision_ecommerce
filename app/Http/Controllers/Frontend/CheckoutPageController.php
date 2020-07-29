<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use App\Order;
use App\Charge;
use App\Deliveryinfo;
use App\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\UserValidationRequest;
use App\Paymentmethod;

class CheckoutPageController extends Controller
{
    public function index(){
        $charges = Charge::first();
        $divisions = Division::all();
        $payment_methods = Paymentmethod::all();
        return view('frontend.checkout.index',compact('divisions','charges','payment_methods'));
    }





    public function store(Request $request){


        $this->validate($request,[
            'name' =>  'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'division' => 'required',
            'district' => 'required',
            'area' => 'required',
            'address' => 'required|max:500',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required|min:8',
            'payment_method' => 'required',
        ]);

        
        $today = Carbon::now()->toDateString(); 
        $delivery_info = Deliveryinfo::first();
        $delivery_delay = $delivery_info->delay;
        $next_shipping_date = date('Y-m-d', strtotime($today .' +'.$delivery_delay.' day'));

        $charges = Charge::first();
         //Amount calculation
         $products = Session::get('ShoppingCart');

         $amount = [];
         foreach($products as  $item){
             $amount[] = ($item['count'])*($item['price']);
         }
         $netamount = array_sum($amount);
         $disc_amount = $netamount*($charges->discount/100);
         $subtotal = $netamount-$disc_amount;
         $vat_tax = $charges->vat + $charges->tax;
         $vat_tax_amount = $subtotal*($vat_tax/100);
         $shipping = $charges->shipping;
 
         $amount_total =  ($subtotal+$vat_tax_amount+$shipping);



        
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->division_id = $request->division;
        $user->district_id = $request->district;
        $user->area_id = $request->area;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->save();
        $credentials = array(
            'email' => $request->email,
            'password' => $request->password,
        );
       
        
        $order = new Order;
        $order->user_id = $user->id;
        $order->discount = $charges->discount;
        $order->vat = $charges->vat;
        $order->tax = $charges->tax;
        $order->shipping = $charges->shipping;
        $order->payment_method = $request->payment_method;
        $order->payment_status = 0;
        $order->shipping_status = 0;
        $order->shipping_date = $next_shipping_date;
        $order->order_status = 0;
        $order->amount = $amount_total;
        $order->invoice_id = $request->user_id.time().rand(1,500);
        $order->division_id = $request->division;
        $order->district_id = $request->district;
        $order->area_id = $request->area;
        $order->address = $request->address;
        $order->ordered_at = now();
        if($request->has('txn_id')){
            $order->txn_id = $request->txn_id;
        }
        $order->save();
        $cart = [];
        foreach(Session::get('ShoppingCart') as $shopping_cart){
            $cart[] = ['order_id' =>$order->id, 'product_id' => $shopping_cart['id'],'user_id'=> $user->id,'qty' => $shopping_cart['count'],'price' => $shopping_cart['price'],'ordered_at' => now()];        
        }

        $order->product()->attach($cart);

        Session::forget('ShoppingCart');
        Session::flash('OrderCompleted',true);

       


        if (Auth::attempt($credentials,'on')) {
            return redirect(route('order.confirmation',$order->id));
        }else{
            return redirect(route('order.confirmation',$order->id));
        }


        
        

        
    }

    public function oldcustomerorder(Request $request, $id){
        $this->validate($request,[
            'phone' => 'required',
            'division' => 'required',
            'district' => 'required',
            'area' => 'required',
            'address' => 'required|max:500',
            'payment_method' => 'required',
        ]);


         $today = Carbon::now()->toDateString(); 
        $delivery_info = Deliveryinfo::first();
        $delivery_delay = $delivery_info->delay;
        $next_shipping_date = date('Y-m-d', strtotime($today .' +'.$delivery_delay.' day'));

        $charges = Charge::first();

         //Amount calculation
         $products = Session::get('ShoppingCart');

         $amount = [];
         foreach($products as  $item){
             $amount[] = ($item['count'])*($item['price']);
         }
         $netamount = array_sum($amount);
         $disc_amount = $netamount*($charges->discount/100);
         $subtotal = $netamount-$disc_amount;
         $vat_tax = $charges->vat + $charges->tax;
         $vat_tax_amount = $subtotal*($vat_tax/100);
         $shipping = $charges->shipping;
 
         $amount_total =  ($subtotal+$vat_tax_amount+$shipping);


        //user info update

        $user =  User::findOrFail($id);
        $user->phone = $request->phone;
        $user->save();


         
        //order 
        
        $order = new Order;
        $order->user_id = Auth::user()->id;
        $order->discount = $charges->discount;
        $order->vat = $charges->vat;
        $order->tax = $charges->tax;
        $order->shipping = $charges->shipping;
        $order->shipping_date = $next_shipping_date;
        $order->payment_method = $request->payment_method;;
        $order->payment_status = 0;
        $order->shipping_status = 0;
        $order->order_status = 0;
        $order->division_id = $request->division;
        $order->district_id = $request->district;
        $order->area_id = $request->area;
        $order->address = $request->address;
        $order->amount = $amount_total;
        $order->invoice_id = $request->user_id.time().rand(1,500);
        $order->ordered_at = now();
        if($request->has('txn_id')){
            $order->txn_id = $request->txn_id;
        }
        $order->save();



        $cart = [];
        foreach(Session::get('ShoppingCart') as $shopping_cart){
            $cart[] = ['order_id' =>$order->id, 'product_id' => $shopping_cart['id'],'user_id'=>  Auth::user()->id,'qty' => $shopping_cart['count'],'price' => $shopping_cart['price'],'ordered_at' => now()];        
        }

        $order->product()->attach($cart);
        Session::forget('ShoppingCart');
        Session::flash('OrderCompleted',true);
        return redirect(route('order.confirmation',$order->id));

    }

   
}
