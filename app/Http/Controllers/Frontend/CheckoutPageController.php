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
            'cartdata' => 'required',
            'division' => 'required',
            'address' => 'required|max:500',
            'password' => 'required|confirmed|min:8|max:14',
            'password_confirmation' => 'required|min:8|max:14',
            'payment_method' => 'required',
        ]);

        $cartdata = json_decode($request->cartdata);
        $products = json_decode($cartdata);
        
        $today = Carbon::now()->toDateString(); 
        $delivery_info = Deliveryinfo::first();
        $delivery_delay = $delivery_info->delay;
        $next_shipping_date = date('Y-m-d', strtotime($today .' +'.$delivery_delay.' day'));

        $charges = Charge::first();


        $amount = [];
        foreach($products as  $item){
            $amount[] = ($item->count)*($item->price);
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
        $user->section_id = 3;
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
        $order->address = $request->address;
        $order->ordered_at = now();
        if($request->has('txn_id')){
            $order->txn_id = $request->txn_id;
        }
        $order->save();
        $cart = [];
        $smspdinfo = "";
        foreach($products as $shopping_cart){
            $smspdinfo .= $shopping_cart->o_name." = ".$shopping_cart->count." x ".$shopping_cart->price." = ".round($shopping_cart->count)*round($shopping_cart->price).",";

            $cart[] = ['order_id' =>$order->id, 'product_id' => $shopping_cart->id,'user_id'=> $user->id,'qty' => $shopping_cart->count,'price' => $shopping_cart->price,'ordered_at' => now()];        
        }

        $order->product()->attach($cart);



        // $url = "http://66.45.237.70/api.php";
        // $number= $user->phone;
        // $adminnumber = "01700817934";

        // $text = $user->name." Your Order has been received. Product:".$smspdinfo."Discount: ".$disc_amount." Delivery Charge = ". $shipping." Tk.Total Payable Amount = ".$order->amount." Tk.(Vision Mart)";

        // $text2 = "New Ecommerce Order in Visionmart Customer: ".$user->name." Product: ".$smspdinfo." Address: ".$order->address.". Total Payable Amount = ".$order->amount.". Please Review The Order In Ecommerce Dashboard";

        // $data= array(
        // 'username'=>"shajibazher",
        // 'password'=>"UtUs6B8WVqjmm72",
        // 'number'=>"$number",
        // 'message'=>"$text"
        // );

        // $data2 = array(
        //     'username'=>"shajibazher",
        //     'password'=>"UtUs6B8WVqjmm72",
        //     'number'=>"$adminnumber",
        //     'message'=>"$text2"
        // );
        // $ch = curl_init(); // Initialize cURL
        // curl_setopt($ch, CURLOPT_URL,$url);


        // //Sms For Customer
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus1 = $p[0];

        // //Sms For Admin

        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data2));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus2 = $p[0];




        Session::flash('OrderCompleted',true);

        if (Auth::attempt($credentials,'on')) {
            return redirect(route('order.confirmation',$order->invoice_id));
        }else{
            return redirect(route('order.confirmation',$order->invoice_id));
        }


        
    }

    public function oldcustomerorder(Request $request, $id){
        $this->validate($request,[
            'cartdata' => 'required',
            'phone' => 'required',
            'division' => 'required',
            'address' => 'required|max:500',
            'payment_method' => 'required',
        ]);

        if(strlen($request->cartdata) < 5){
             Session::flash('err','There Is No Product On The Cart');
             return redirect()->back();

        }



        $cartdata = json_decode($request->cartdata);
        $products = json_decode($cartdata);
        

         $today = Carbon::now()->toDateString(); 
        $delivery_info = Deliveryinfo::first();
        $delivery_delay = $delivery_info->delay;
        $next_shipping_date = date('Y-m-d', strtotime($today .' +'.$delivery_delay.' day'));

        $charges = Charge::first();

         //Amount calculation
        

         $amount = [];
         foreach($products as  $item){
             $amount[] = ($item->count)*($item->price);
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
        $order->address = $request->address;
        $order->amount = $amount_total;
        $order->invoice_id = $request->user_id.time().rand(1,500);
        $order->ordered_at = now();
        if($request->has('txn_id')){
            $order->txn_id = $request->txn_id;
        }
        $order->save();



        $cart = [];
        $smspdinfo = "";
        foreach($products as $shopping_cart){
            $cart[] = ['order_id' =>$order->id, 'product_id' => $shopping_cart->id,'user_id'=>  Auth::user()->id,'qty' => $shopping_cart->count,'price' => $shopping_cart->price,'ordered_at' => now()];     
            
            $smspdinfo .= $shopping_cart->o_name." = ".$shopping_cart->count." x ".$shopping_cart->price." = ".round($shopping_cart->count)*round($shopping_cart->price).",";
        }

        $order->product()->attach($cart);


        // $url = "http://66.45.237.70/api.php";
        // $number= $user->phone;
        // $adminnumber = "01700817934";

        // $text = $user->name." Your Order has been received. Product:".$smspdinfo."Discount: ".$disc_amount." Delivery Charge = ". $shipping." Tk.Total Payable Amount = ".$order->amount." Tk.(Vision Mart)";

        // $text2 = "New Ecommerce Order in Visionmart Customer: ".$user->name." Product: ".$smspdinfo." Address: ".$order->address.". Total Payable Amount = ".$order->amount.". Please Review The Order In Ecommerce Dashboard";

        // $data= array(
        // 'username'=>"shajibazher",
        // 'password'=>"UtUs6B8WVqjmm72",
        // 'number'=>"$number",
        // 'message'=>"$text"
        // );

        // $data2 = array(
        //     'username'=>"shajibazher",
        //     'password'=>"UtUs6B8WVqjmm72",
        //     'number'=>"$adminnumber",
        //     'message'=>"$text2"
        // );
        // $ch = curl_init(); // Initialize cURL
        // curl_setopt($ch, CURLOPT_URL,$url);


        // //Sms For Customer
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus1 = $p[0];

        // //Sms For Admin

        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data2));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus2 = $p[0];
        Session::flash('OrderCompleted',true);
        return redirect(route('order.confirmation',$order->invoice_id));

    }

   
}
