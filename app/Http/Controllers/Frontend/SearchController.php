<?php

namespace App\Http\Controllers\Frontend;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index(Request $request){
        $qry = $request->s;
        $products = Product::where('type','ecom')->where('product_name', 'LIKE', "%$qry%")->get();
        return view('frontend.search.index',compact('products'));
    }
    
}
