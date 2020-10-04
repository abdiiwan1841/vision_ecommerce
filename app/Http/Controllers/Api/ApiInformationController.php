<?php

namespace App\Http\Controllers\Api;
use Session;
use App\Area;
use App\Cash;
use App\Sale;
use App\User;
use App\Admin;
use App\Order;
use App\Company;
use App\Prevdue;
use App\Product;
use App\District;
use App\Purchase;
use App\Supplier;
use Carbon\Carbon;
use App\Paymentmethod;
use App\Returnproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ApiInformationController extends Controller
{
    public function districtinfo($id){
        return District::where('division_id',$id)->get();
    }
    public function areainfo($id){
        return Area::where('district_id',$id)->get();
    }
    
    public function userinfo($id){
        return User::findOrFail($id);
    }
    public function supplierinfo($id){
        return Supplier::findOrFail($id);
    }

    public function productinfo($id){

        $sell =  DB::table('product_sale')->where('product_id', '=', $id)->sum('qty');
        $free =  DB::table('product_sale')->where('product_id', '=', $id)->sum('free');
        $purchase = DB::table('product_purchase')->where('product_id', '=', $id)->sum('qty');
        $order = DB::table('order_product')->where('product_id', '=', $id)->sum('qty');
        $return = DB::table('product_returnproduct')->where('product_id', '=', $id)->sum('qty');
        $damage = DB::table('damage_product')->where('product_id', '=', $id)->sum('qty');
        $stock = ($purchase+$return) -  ($order+$sell+$free+$damage);
      
        return [Product::with('size')->findOrFail($id),$stock];
    }
    

    public function orderinfo($id){
        $order =  Order::with('product')->findOrFail($id);

        
        foreach($order->product as $pd){
            $name = preg_replace('/\s+/', '', $pd->product_name);
            $pdarray[] = [ "name" => $name, "price" => $pd->pivot->price,"count" => $pd->pivot->qty, "id" => $pd->pivot->product_id, "o_name" => $pd->product_name,"image" => $pd->image ];
        }

        $pdJSON = ["orderCart" =>  $pdarray,"order_date" => Carbon::createFromFormat('Y-m-d H:i:s', $order->ordered_at)->format('Y-m-d'),"user_id" => $order->user_id, "order_id" => $order->id];

        return $pdJSON;

    }


    public function pendingSalesInfo($id){
        return Sale::with('product','user')->findOrFail($id);
    }


    
    public function pendingDeliveryInfo($id){
        return Sale::with('product','user')->findOrFail($id);
    }

    


    public function pendingReturnInfo($id){
        return Returnproduct::with('product','user')->findOrFail($id);
    }

    public function saleinfo($id){
        $sale =  Sale::with('product')->findOrFail($id);

        
        foreach($sale->product as $pd){
            $name = preg_replace('/\s+/', '', $pd->product_name);
            $pdarray[] = [ "name" => $name, "price" => $pd->pivot->price,"count" => $pd->pivot->qty,"id" => $pd->pivot->product_id, "o_name" => $pd->product_name,"image" => $pd->image,"free"=> $pd->pivot->free];
        }

        $pdJSON = ["salesCart" =>  $pdarray,"sales_date" => Carbon::createFromFormat('Y-m-d H:i:s', $sale->sales_at)->format('Y-m-d'),"user_id" => $sale->user_id, "sale_id" => $sale->id];

        return $pdJSON;

    }

    public function purchaseinfo($id){
        $purchase =  Purchase::with('product')->findOrFail($id);

        
        foreach($purchase->product as $pd){
            $name = preg_replace('/\s+/', '', $pd->product_name);
            $pdarray[] = [ "name" => $name, "price" => $pd->pivot->price,"count" => $pd->pivot->qty, "id" => $pd->pivot->product_id, "o_name" => $pd->product_name,"image" => $pd->image ];
        }

        $pdJSON = ["purchaseCart" =>  $pdarray,"purchase_date" => Carbon::createFromFormat('Y-m-d H:i:s', $purchase->purchased_at)->format('Y-m-d'),"supplier_id" => $purchase->supplier_id, "purchase_id" => $purchase->id];

        return $pdJSON;

    }

    public function returninfo($id){
        $return =  Returnproduct::with('product')->findOrFail($id);

        
        foreach($return->product as $pd){
            $name = preg_replace('/\s+/', '', $pd->product_name);
            $pdarray[] = [ "name" => $name, "price" => $pd->pivot->price,"count" => $pd->pivot->qty, "id" => $pd->pivot->product_id, "o_name" => $pd->product_name,"image" => $pd->image ];
        }

        $pdJSON = ["returnCart" =>  $pdarray,"return_date" => Carbon::createFromFormat('Y-m-d H:i:s', $return->returned_at)->format('Y-m-d'),"user_id" => $return->user_id, "return_id" => $return->id];

        return $pdJSON;
    }

    public function paymentmethodinfo($id){
        return Paymentmethod::findOrFail($id);
    }

    public function social(){
        $company = Company::first();
        return $company;
    }

    public function priceInfo($id){
        return Product::findOrFail($id);
    }


    public function invdueinfo($id){
        $prevdue = Prevdue::where('user_id',$id)->sum('amount');
        $sales = Sale::where('user_id',$id)->sum('amount');
        $cashes = Cash::where('user_id',$id)->where('status',1)->sum('amount');
        $returns = Returnproduct::where('user_id',$id)->sum('amount');
        return ($sales+$prevdue)-($cashes+$returns);
    }

    public function deliveryman(){
        return Admin::where('role_id',4)->get();
    }

    public function dynamicProduct($limit=10){
        $total_products = Product::where('type','ecom')->count('id');
        $dynamic_products = Product::with('brand','category','size','subcategory')->where('type','ecom')->orderBy('id','desc')->take($limit)->get();
        return ['total_products' => $total_products,'dynamic_products' => $dynamic_products];
    }
}
