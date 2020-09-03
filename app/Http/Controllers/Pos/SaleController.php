<?php

namespace App\Http\Controllers\Pos;

use PDF;
use App\Sale;
use App\User;
use App\Admin;
use App\Charge;
use App\Product;
use Carbon\Carbon;
use App\GeneralOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{

    public function index()
    {
        $sales = Sale::withTrashed('user','prouduct')->take(10)->orderBy('id', 'desc')->get();
        return view('pos.sale.index',compact('sales'));
    }

    public function result(Request $request){
        $this->validate($request,[
            'start' => 'required|date',
            'end' => 'required|date',
        ]);


        $sales = Sale::withTrashed('user','prouduct')->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('sales_at', 'asc')->get();
        return view('pos.sale.salesresult',compact('sales','request'));
    }


    public function create()
    {
        $charge = Charge::first();
        $users = User::where('user_type','pos')->get();
        $products = Product::get();
        return view('pos.sale.create',compact('products','users','charge'));
    }


    public function store(Request $request)
    {
             
        $this->validate($request,[
            'sales_date' => 'required|date',
            'user_id' => 'required|numeric',
            'discount' => 'required|numeric',
            'carrying_and_loading' => 'required|numeric',
            'product' => 'required',
        ]);

        
        //Amount calculation
        $products = json_decode($request->product);

        $amount = [];
        foreach($products as  $item){
            $amount[] = ($item->count)*($item->price);
        }
        $netamount = array_sum($amount);
        $amount_total = ($netamount+$request->carrying_and_loading)-($request->discount);

        $sales = new Sale;
        $sales->user_id = $request->user_id;
        $sales->discount = $request->discount;
        $sales->carrying_and_loading = $request->carrying_and_loading;
        $sales->sales_at = $request->sales_date." ".Carbon::now()->toTimeString();
        $sales->amount = $amount_total;
        $sales->sales_status = 0;
        $sales->provided_by = Auth::user()->name;
        $sales->save();

        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['sale_id' =>$sales->id, 'product_id' => $product->id,'qty' => $product->count,'free' => $product->free,'price' => $product->price, 'user_id' => $request->user_id,'sales_at' => $request->sales_date." ".Carbon::now()->toTimeString()];   
        }

        $sales->product()->attach($product_info);

        
        // $url = "http://66.45.237.70/api.php";
        // $number="01736402322";
        // $text="New Invoice, Date: ".$sales->sales_at->format('d-m-Y')." ID:# ".$sales->id."  ".$sales->user->name.",  ".$sales->user->address.", Amount: ".$sales->amount." .Please Approve,Thanks";
        // $data= array(
        // 'username'=>"shajibazher",
        // 'password'=>"UtUs6B8WVqjmm72",
        // 'number'=>"$number",
        // 'message'=>"$text"
        // );

        // $ch = curl_init(); // Initialize cURL
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus = $p[0];
        // if($sendstatus == 1101){
        //     Toastr::success('Sales Invoice Created Successfully An Sms has been Sent to respected Phone Number For order Approval', 'success');
        // }else{
        //     Toastr::success('Sales Created Successfully', 'success');
        //     Toastr::error(VisionSmsResponse($sendstatus), 'error');
        // }

        return $sales->id;
    }




    public function show($id)
    {
        $sale = Sale::findOrFail($id);
        if(empty($sale->approved_by)){
            $signature = null;
        }else{
            $signature = Admin::where('id',$sale->approved_by)->select('name','signature')->first();
        }
        if(empty($sale->delivered_by)){
            $delivered_by = null;
        }else{
            $delivered_by = Admin::where('id',$sale->delivered_by)->select('name')->first();
        }
        
        return view('pos.sale.show',compact('sale','signature','delivered_by'));
    }


    public function edit(Sale $sale)
    {
        $charge = Charge::first();
        $users = User::where('user_type','pos')->get();
        $products = Product::get();
        return view('pos.sale.edit',compact('products','users','sale'));
    }


    public function update(Request $request, Sale $sale)
    {
        $this->validate($request,[
            'sales_date' => 'required|date',
            'user_id' => 'required|numeric',
            'discount' => 'required|numeric',
            'carrying_and_loading' => 'required|numeric',
            'product' => 'required',
        ]);

        
        //Amount calculation
        $products = json_decode($request->product);

        $amount = [];
        foreach($products as  $item){
            $amount[] = ($item->count)*($item->price);
        }
        $netamount = array_sum($amount);
        $amount_total = ($netamount+$request->carrying_and_loading)-($request->discount);


        $sale->user_id = $request->user_id;
        $sale->discount = $request->discount;
        $sale->carrying_and_loading = $request->carrying_and_loading;
        $sale->sales_at = $request->sales_date." ".Carbon::now()->toTimeString();
        $sale->sales_status = 0;
        $sale->approved_by = null;
        $sale->edited = 1;
        $sale->amount = $amount_total;
        $sale->save();


        DB::table('product_sale')->where('sale_id', '=', $sale->id)->delete();
  

        
        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['sale_id' =>$sale->id, 'product_id' => $product->id,'qty' => $product->count,'free' => $product->free,'price' => $product->price, 'user_id' => $request->user_id,'sales_at' => $request->sales_date." ".Carbon::now()->toTimeString()];   
        }


        $sale->product()->attach($product_info);


        // $url = "http://66.45.237.70/api.php";
        // $number="01736402322";
        // $text="A Sales Invoice Is Edited By ".Auth::user()->name.", Invoice Date: ".$sale->sales_at->format('d-m-Y')." ID:# ".$sale->id."  ".$sale->user->name. " Please Approve,Thanks";
        // $data= array(
        // 'username'=>"shajibazher",
        // 'password'=>"UtUs6B8WVqjmm72",
        // 'number'=>"$number",
        // 'message'=>"$text"
        // );

        // $ch = curl_init(); // Initialize cURL
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus = $p[0];
        // if($sendstatus == 1101){
        //     Toastr::success('Sales Invoice Updated Successfully An Sms has been Sent to respected Phone Number: '.$number.' For Approval', 'success');
        // }else{
        //     Toastr::success('Sales Updated Successfully', 'success');
        //     Toastr::error(VisionSmsResponse($sendstatus), 'error');
        // }
        return $sale->id;
    }


    public function destroy(Sale $sale)
    {
        if(Auth::user()->role->id == 2){
            Toastr::error('You Are Not Authorized', 'error');
            return redirect()->back();
        }else{
        $sale->deleted_at = now();
        $sale->sales_status = 2;
        $sale->approved_by = Auth::user()->id;
        $sale->amount = 0;
        $sale->save();
        $sale->product()->detach();
        Toastr::success('Sales cancelled Successfully', 'success');
        return redirect()->route('returnproduct.index');
        }
    }

    public function invoice(Request $request,$id){
        $general_opt = GeneralOption::first();
        $general_opt_value = json_decode($general_opt->options, true);
        $sale = Sale::findOrFail($id);
        $current_user = User::findOrFail($sale->user_id);
        if(empty($sale->approved_by)){
            $signature = null;
        }else{
            $signature = Admin::where('id',$sale->approved_by)->select('name','signature')->first();
        }
        


        $pdf = PDF::loadView('pos.sale.invoice',compact('sale','current_user','general_opt_value','signature'));
        return $pdf->download('invoice.pdf');
    }

    public function approve(Request $request,$id){
        $sale = Sale::with('product')->findOrFail($id);
        if(Auth::user()->role->id != 1){
           return ['id'=> $sale->id,'status' => $sale->sales_status,'msg' => 'You Are Not Authorized' ];
            
        }else{
       
        $sale->sales_status = 1;
        $sale->approved_by = Auth::user()->id;
        $sale->save();

        //check if the invoice is not edited then send sms
        if($sale->edited != 1){
        //For Sent Product To Sms Product 
        // $pdinfo = "";
        // foreach($sale->product as $pd){
        //     $pdinfo .= $pd->product_name." = ".$pd->pivot->qty.",";
        // }


        // $url = "http://66.45.237.70/api.php";
        // $number="01724136687,01958454158";
        // $text="New Invoice Approved By ".Auth::user()->name.", Date: ".$sale->sales_at->format('d-m-Y')." Customer: ".$sale->user->name.",  ".$sale->user->address." Amount= ".$sale->amount.". Check Details On Site, Thanks";
        // $data= array(
        // 'username'=>"shajibazher",
        // 'password'=>"UtUs6B8WVqjmm72",
        // 'number'=>"$number",
        // 'message'=>"$text"
        // );

        // $ch = curl_init(); // Initialize cURL
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus = $p[0];
        // if($sendstatus == 1101){
        //     Toastr::success('Invoice Approved Successfully An sms has been sent to '.$number, 'success');
        // }else{
        //     Toastr::success('Invoice Approved Successfully', 'success');
        //     Toastr::error(VisionSmsResponse($sendstatus), 'error');
        // }

        }
        //endcheck if invoice is edited
        
        return ['id'=> $sale->id,'status' => $sale->sales_status,'msg' => 'Sales Invoice Approved Successfully' ];
        }
    }

    public function delivery($id){
        $sale = Sale::findOrFail($id);
        if(Auth::user()->role->id != 4){
            return ['id'=> $sale->id,'status' => $sale->delivery_status,'msg' => 'You Are Not Authorized' ];
        }else{
        
        $sale->timestamps = false;
        $sale->delivery_status = 1;
        $sale->delivered_by = Auth::user()->id;
        $sale->save();
        return ['id'=> $sale->id,'status' => $sale->delivery_status,'msg' => 'Invoice Mark as Delivered' ];
        }
    }
}
