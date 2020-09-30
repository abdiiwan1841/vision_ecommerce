<?php

namespace App\Http\Controllers;

use App\Product;
use App\Purchase;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('purchase.index');
    }

    public function result(Request $request){
        $this->validate($request,[
            'start' => 'required|date',
            'end' => 'required|date',
        ]);


        $purchases = Purchase::withTrashed('supplier','prouduct')->whereBetween('purchased_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('purchased_at', 'asc')->get();
        return view('purchase.purchaseresult',compact('purchases','request'));
    }




    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase.create',compact('products','suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
        $this->validate($request,[
            'purchase_date' => 'required|date',
            'supplier_id' => 'required|numeric',
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



        $p_date = $request->purchase_date." ".Carbon::now()->toTimeString();

        $purchase = new Purchase;
        $purchase->supplier_id = $request->supplier_id;
        $purchase->amount = $amount_total;
        $purchase->purchased_at = $p_date;
        $purchase->discount = $request->discount;
        $purchase->carrying_and_loading = $request->carrying_and_loading;
        $purchase->save();

        
        $products = json_decode($request->product);
        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['purchase_id' =>$purchase->id, 'product_id' => $product->id,'qty' => $product->count,'price' => $product->price, 'mfg' => $product->mfg, 'exp' => $product->exp, 'supplier_id' => $request->supplier_id,'purchased_at' => $p_date];   
        }
        $purchase->product()->attach($product_info);
        Toastr::success('Purchased Successfully', 'success');
        return $purchase->id;
        
    }

    public function show($id)
    {
        $purchaseDetails = Purchase::with('product','supplier')->findOrFail($id);
        return view('purchase.show',compact('purchaseDetails'));
    }


    
    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase.edit',compact('products','suppliers','purchase'));
    }


    public function update(Request $request,$id)
    {
     
        $this->validate($request,[
            'purchase_date' => 'required|date',
            'supplier_id' => 'required|numeric',
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



        $p_date = $request->purchase_date." ".Carbon::now()->toTimeString();

        $purchase = Purchase::findOrFail($id);
        $purchase->supplier_id = $request->supplier_id;
        $purchase->amount = $amount_total;
        $purchase->purchased_at = $p_date;
        $purchase->discount = $request->discount;
        $purchase->carrying_and_loading = $request->carrying_and_loading;
        $purchase->save();

        
        $products = json_decode($request->product);
        $product_info = [];

         DB::table('product_purchase')->where('purchase_id', '=', $id)->delete();

        foreach($products as $product){
         $product_info[] = ['purchase_id' =>$purchase->id, 'product_id' => $product->id,'qty' => $product->count,'price' => $product->price,'mfg' => $product->mfg, 'exp' => $product->exp, 'supplier_id' => $request->supplier_id,'purchased_at' => $p_date];   
        }
        

        $purchase->product()->attach($product_info);
    

        Toastr::success('Purchase Updated Successfully', 'success');
        return $purchase->id;
        
    }


    public function destroy($id)
    {
        $purchaseDetails = Purchase::findOrFail($id);
        $purchaseDetails->deleted_at = now();
        $purchaseDetails->save();
        $purchaseDetails->product()->detach();
        Toastr::success('Purchased cancelled Successfully', 'success');
        return redirect()->route('purchase.index');
    }
}
