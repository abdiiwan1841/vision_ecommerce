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
        return view('pos.sale.index');
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

        Toastr::success('Sales Created Successfully', 'success');
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
        
        return view('pos.sale.show',compact('sale','signature'));
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
        $sale->amount = $amount_total;
        $sale->save();


        DB::table('product_sale')->where('sale_id', '=', $sale->id)->delete();
  

        
        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['sale_id' =>$sale->id, 'product_id' => $product->id,'qty' => $product->count,'free' => $product->free,'price' => $product->price, 'user_id' => $request->user_id,'sales_at' => $request->sales_date." ".Carbon::now()->toTimeString()];   
        }


        $sale->product()->attach($product_info);


        Toastr::success('Sales Created Successfully', 'success');
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
        $sale->amount = 0;
        $sale->save();
        $sale->product()->detach();
        Toastr::success('Sales cancelled Successfully', 'success');
        return redirect(route('admin.inventorydashboard'));
        }
    }

    public function invoice(Request $request,$id){
        $general_opt = GeneralOption::first();
        $general_opt_value = json_decode($general_opt->options, true);
        $sale = Sale::findOrFail($id);
        $current_user = User::findOrFail($sale->user_id);
        $signature = Admin::where('id',$sale->approved_by)->select('name','signature')->first();


        $pdf = PDF::loadView('pos.sale.invoice',compact('sale','current_user','general_opt_value','signature'));
        return $pdf->download('invoice.pdf');
    }

    public function approve(Request $request,$id){
        if(Auth::user()->role->id == 2){
            Toastr::error('You Are Not Authorized', 'error');
            return redirect()->back();
        }else{
        $sale = Sale::findOrFail($id);
        $sale->sales_status = 1;
        $sale->approved_by = Auth::user()->id;
        $sale->save();
        Toastr::success('Sales Approved Successfully', 'success');
        return redirect()->back();
        }
    }
}
