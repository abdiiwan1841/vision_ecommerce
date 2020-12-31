<?php

namespace App\Http\Controllers;

use App\Sale;
use App\Damage;
use App\Product;
use Carbon\Carbon;
use App\GeneralOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class StockController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:Stock View')->only('index','purchasehistory','saleshistory','returnhistory','orderhistory','damagehistory','freehistory');
        $this->middleware('permission:Stock Report')->only('stockreport','stockreportshow','stockreportpdf','export');
    }

    
    public function index(){
        $products = Product::all();
        $stockinfo= array();
        
        foreach($products as $product){
            $sale =  DB::table('product_sale')->where('product_id', '=', $product->id)->sum('qty');
            $free =  DB::table('product_sale')->where('product_id', '=', $product->id)->sum('free');
            $purchase = DB::table('product_purchase')->where('product_id', '=', $product->id)->sum('qty');
            $order = DB::table('order_product')->where('product_id', '=', $product->id)->sum('qty');
            $return = DB::table('product_returnproduct')->where('product_id', '=', $product->id)->sum('qty');
            $damage = DB::table('damage_product')->where('product_id', '=', $product->id)->sum('qty');
          
            $stockinfo[$product->product_name] = ['id' => $product->id,'sale' =>  $sale,'free' => $free, 'purchase'=> $purchase, 'order' => $order,'return' =>  $return,'damage'=> $damage,'mfg'=> $product->mfg, 'exp' => $product->exp  ];
            
        }
        return view('admin.stock.index',compact('stockinfo'));
    }

    public function purchasehistory($id){
        $product = Product::findOrFail($id);
        $purchase_history = DB::table('product_purchase')->join('products','product_purchase.product_id','=','products.id')->join('suppliers','product_purchase.supplier_id','=','suppliers.id')->select('product_purchase.*','products.product_name','suppliers.name')->where('product_id', '=', $id)->orderBy('purchased_at','desc')->get();
        
        return view('admin.stock.purchasehistory',compact('product','purchase_history'));
    }

    public function saleshistory($id){
        $product = Product::findOrFail($id);
        $sales_history = DB::table('product_sale')->join('products','product_sale.product_id','=','products.id')->join('users','product_sale.user_id','=','users.id')->select('product_sale.*','products.product_name','users.name')->where('product_id', '=', $id)->orderBy('sales_at','desc')->get();
        
        return view('admin.stock.saleshistory',compact('product','sales_history'));
    }

    public function returnhistory($id){
        $product = Product::findOrFail($id);
        $return_history = DB::table('product_returnproduct')->join('products','product_returnproduct.product_id','=','products.id')->join('users','product_returnproduct.user_id','=','users.id')->select('product_returnproduct.*','products.product_name', 'users.name')->where('product_id', '=', $id)->orderBy('returned_at','desc')->get();

        return view('admin.stock.returnhistory',compact('product','return_history'));
    }

    public function orderhistory($id){
        $product = Product::findOrFail($id);
        $sales_history = DB::table('order_product')->join('products','order_product.product_id','=','products.id')->join('users','order_product.user_id','=','users.id')->select('order_product.*','products.product_name', 'users.name')->where('product_id', '=', $id)->orderBy('ordered_at','desc')->get();

        return view('admin.stock.orderhistory',compact('product','sales_history'));
    }

    public function damagehistory($id){
        $product = Product::findOrFail($id);
        $damage_history = DB::table('damage_product')->join('products','damage_product.product_id','=','products.id')->select('damage_product.*','products.product_name')->where('product_id', '=', $id)->orderBy('damaged_at','desc')->get();

        return view('admin.stock.damagehistory',compact('product','damage_history'));
    }

    public function freehistory($id){
        $product = Product::findOrFail($id);
        $free_history = DB::table('product_sale')->join('products','product_sale.product_id','=','products.id')->join('users','product_sale.user_id','=','users.id')->select('product_sale.*','products.product_name','users.name')->where('product_sale.product_id', '=', $id)->where('product_sale.free', '>', 0)->orderBy('sales_at','desc')->get();
        
        return view('admin.stock.freehistory',compact('product','free_history'));
    }







    public function stockreport(){
        return view('admin.stock.report');
    }

    



    public function stockreportshow(Request $request){
        $request->validate([
            'start' => ['required'],
            'end' => ['required'],
        ]);

        function stock($id){
            $sell =  DB::table('product_sale')->where('product_id', '=', $id)->sum('qty');
            $free =  DB::table('product_sale')->where('product_id', '=', $id)->sum('free');
            $purchase = DB::table('product_purchase')->where('product_id', '=', $id)->sum('qty');
            $order = DB::table('order_product')->where('product_id', '=', $id)->sum('qty');
            $return = DB::table('product_returnproduct')->where('product_id', '=', $id)->sum('qty');
            $damage = DB::table('damage_product')->where('product_id', '=', $id)->sum('qty');
    
            
            $stock = ($purchase+$return) -  ($order+$sell+$damage+$free);
            return $stock;
        }
        
        $products = Product::orderBy('product_name','ASC')->get();;
        $stock = [];
        foreach($products as $pd){
            $all_qty = stock($pd->id);

            $return_qty = DB::table('product_returnproduct')->where('product_id' ,'=', $pd->id)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('qty');

            $purchase_qty = DB::table('product_purchase')->where('product_id' ,'=', $pd->id)->whereBetween('purchased_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('qty');

            $order_qty = DB::table('order_product')->where('product_id' ,'=', $pd->id)->whereBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('qty');

            $sell_qty = DB::table('product_sale')->where('product_id' ,'=', $pd->id)->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('qty');

            $free_qty = DB::table('product_sale')->where('product_id' ,'=', $pd->id)->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('free');

            $damage_qty = DB::table('damage_product')->where('product_id' ,'=', $pd->id)->whereBetween('damaged_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('qty');

            $current_date_range_qty = ($return_qty+$purchase_qty)-($order_qty+$sell_qty+$damage_qty);

            $prev_qty = $all_qty-$current_date_range_qty;
 


            $stock[] = ['product_id' => $pd->id, 'product_name' => $pd->product_name,'prev_qty'=> $prev_qty, 'sell_qty' => $sell_qty, 'free_qty'=> $free_qty,'return_qty' => $return_qty,'purchase_qty' => $purchase_qty,'order_qty' => $order_qty, 'damage_qty' => $damage_qty, 'current_stock' => $all_qty];
        }

        

        return  view('admin.stock.show',compact('stock', 'request'));
    }


    public function stockreportpdf(Request $request){
        $request->validate([
            'start' => 'required',
            'end' => 'required',
        ]);
        $general_opt = GeneralOption::first();
        $general_opt_value = json_decode($general_opt->options, true);

        function productStock($id){
            $sell =  DB::table('product_sale')->where('product_id', '=', $id)->sum('qty');
            $free =  DB::table('product_sale')->where('product_id', '=', $id)->sum('free');
            $purchase = DB::table('product_purchase')->where('product_id', '=', $id)->sum('qty');
            $order = DB::table('order_product')->where('product_id', '=', $id)->sum('qty');
            $return = DB::table('product_returnproduct')->where('product_id', '=', $id)->sum('qty');
            $damage = DB::table('damage_product')->where('product_id', '=', $id)->sum('qty');
            $stock = ($purchase+$return) -  ($order+$sell+$damage+$free);
            return $stock;
        }
        
        $products = Product::orderBy('product_name','ASC')->get();
        $stock = [];
        foreach($products as $pd){
            $all_qty = productStock($pd->id);

            $return_qty = DB::table('product_returnproduct')->where('product_id' ,'=', $pd->id)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('qty');

            $purchase_qty = DB::table('product_purchase')->where('product_id' ,'=', $pd->id)->whereBetween('purchased_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('qty');

            $order_qty = DB::table('order_product')->where('product_id' ,'=', $pd->id)->whereBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('qty');

            $sell_qty = DB::table('product_sale')->where('product_id' ,'=', $pd->id)->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('qty');

            $free_qty = DB::table('product_sale')->where('product_id' ,'=', $pd->id)->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('free');

            $damage_qty = DB::table('damage_product')->where('product_id' ,'=', $pd->id)->whereBetween('damaged_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('qty');

            $current_date_range_qty = ($return_qty+$purchase_qty)-($order_qty+$sell_qty+$damage_qty);

            $prev_qty = $all_qty-$current_date_range_qty;
 


            $stock[] = ['product_id' => $pd->id, 'product_name' => $pd->product_name,'prev_qty'=> $prev_qty, 'sell_qty' => $sell_qty, 'free_qty'=> $free_qty,'return_qty' => $return_qty,'purchase_qty' => $purchase_qty,'order_qty' => $order_qty, 'damage_qty' => $damage_qty, 'current_stock' => $all_qty];
        }
        
    $pdf = PDF::loadView('admin.stock.stockpdf',compact('stock','request','general_opt_value'));
    return $pdf->download('Stock Report From'.$request->start.'to '.$request->end.'.pdf');
    }

    public function export(Request $request){
        $general_opt = GeneralOption::first();
        $general_opt_value = json_decode($general_opt->options, true);
        function currentproductStock($id){
            $sell =  DB::table('product_sale')->where('product_id', '=', $id)->sum('qty');
            $free =  DB::table('product_sale')->where('product_id', '=', $id)->sum('free');
            $purchase = DB::table('product_purchase')->where('product_id', '=', $id)->sum('qty');
            $order = DB::table('order_product')->where('product_id', '=', $id)->sum('qty');
            $return = DB::table('product_returnproduct')->where('product_id', '=', $id)->sum('qty');
            $damage = DB::table('damage_product')->where('product_id', '=', $id)->sum('qty');
            $stock = ($purchase+$return) -  ($order+$sell+$damage+$free);
            return $stock;
        }
        
        $products = Product::orderBy('product_name','ASC')->get();
        $stock = [];
        foreach($products as $pd){
            $all_qty = currentproductStock($pd->id);
            $stock[] = ['product_id' => $pd->id, 'product_name' => $pd->product_name,'current_stock' => $all_qty];
        }
        
    $pdf = PDF::loadView('admin.stock.stockexport',compact('stock','general_opt_value'));
    return $pdf->download('Stock Report'.time().'.pdf');
    }




}
