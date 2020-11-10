<?php

namespace App\Http\Controllers\Ecom;

use App\User;
use App\Charge;
use App\Product;
use Carbon\Carbon;
use App\Returnproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class ReturnproductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:Ecommerce Section');
    }

    
    public function index()
    {
        $returns = Returnproduct::withTrashed('user','product')->where('type','ecom')->orderBy('returned_at', 'ASC')->get();
        return view('ecom.return.index',compact('returns'));
    }

 



    public function create()
    {
        $charge = Charge::first();
        $users = User::where('user_type','ecom')->get();
        $products = Product::where('type','ecom')->get();
        return view('ecom.return.create',compact('products','users','charge'));
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
            'return_date' => 'required|date',
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



       
        $return = new Returnproduct;
        $return->user_id = $request->user_id;
        $return->discount = $request->discount;
        $return->carrying_and_loading = $request->carrying_and_loading;
        $return->amount = $amount_total;
        $return->returned_at = $request->return_date." ".Carbon::now()->toTimeString();
        $return->returned_by = Auth::user()->name;
        $return->type = 'ecom';
        $return->save();

        
        $products = json_decode($request->product);
        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['returnproduct_id' =>$return->id, 'product_id' => $product->id,'qty' => $product->count,'price' => $product->price, 'user_id' => $request->user_id,'returned_at' => $request->return_date." ".Carbon::now()->toTimeString()];   
        }
        $return->product()->attach($product_info);
        Toastr::success('Returned Successfully', 'success');
        return $return->id;
        
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $returnDetails = Returnproduct::with('product','user')->findOrFail($id);
        return view('ecom.return.show',compact('returnDetails'));
    }


    public function edit($id)
    {

        $return =  Returnproduct::with('product')->findOrFail($id);
        $users = User::where('user_type','ecom')->get();
        $products = Product::where('type','ecom')->get();
        return view('ecom.return.edit',compact('products','users','return'));
    }


    public function update(Request $request,$id){
        $this->validate($request,[
            'return_date' => 'required|date',
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



       
        $return = Returnproduct::findOrFail($id);
        $return->user_id = $request->user_id;
        $return->discount = $request->discount;
        $return->carrying_and_loading = $request->carrying_and_loading;
        $return->amount = $amount_total;
        $return->returned_by = Auth::user()->name;
        $return->returned_at = $request->return_date." ".Carbon::now()->toTimeString();
        $return->save();
        //Delete Prevreturns
        DB::table('product_returnproduct')->where('returnproduct_id', '=', $id)->delete();

        $products = json_decode($request->product);
        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['returnproduct_id' =>$return->id, 'product_id' => $product->id,'qty' => $product->count,'price' => $product->price, 'user_id' => $request->user_id,'returned_at' => $request->return_date." ".Carbon::now()->toTimeString()];   
        }
        $return->product()->attach($product_info);
        Toastr::success('Returned Successfully', 'success');
        return $return->id;
    }


    public function destroy($id)
    {
        $returnDetails = Returnproduct::findOrFail($id);
        $returnDetails->deleted_at = now();
        $returnDetails->amount = 0;
        $returnDetails->save();
        $returnDetails->product()->detach();
        Toastr::success('Return cancelled Successfully', 'success');
        return redirect()->back();
    }
}
