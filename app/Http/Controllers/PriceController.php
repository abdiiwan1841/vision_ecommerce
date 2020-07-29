<?php

namespace App\Http\Controllers;

use Session;
use App\Product;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index(){
        $products = Product::orderBy('product_name', 'ASC')->get();
        return view('price.index',compact('products'));
    }
    public function update(Request $request, $id){
        $this->validate($request,[
            'price' => 'required|numeric',
        ]);
        $product = Product::findOrFail($id);

        if($request->price >=  $product->price){
            $product->price = $request->price;
            $product->discount_price = NULL;
            $product->current_price = $request->price;
        }elseif($request->price < $product->price){
            $product->discount_price = $request->price;
            $product->current_price = $request->price;
        }

        $product->save();
        Session::flash('success','Price Updated Successfully');
        return redirect()->back();
    }
}
