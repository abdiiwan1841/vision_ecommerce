<?php

namespace App\Http\Controllers\Frontend;

use App\Deal;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SingleProductController extends Controller
{
    public function show($id){
        $single_product = Product::where('type','ecom')->with('brand')->findOrFail($id);
        $simiar_product = Product::where('type','ecom')->where('id','!=',$id)->where('subcategory_id',$single_product->subcategory_id)->take(3)->get();
        return view('frontend.product.show',compact('single_product','simiar_product'));
    }

    public function deal($id){
        $deal = Deal::with('dealproduct')->where('status',1)->first();
        if($deal == null){
           return "sorry no deal found";
        }else{
            $deal_exp = $deal_expire_date = $deal->expired_at->toDateTimeString();
            $current_datetime = Carbon::now()->toDateTimeString();
            if(strtotime($current_datetime) > strtotime($deal_exp)){
                DB::table('deals')->where('id', $deal->id)->update(['status' => false]);
            }
            $single_product = Product::where('type','ecom')->with('brand')->findOrFail($id);
            return view('frontend.product.deal',compact('single_product','deal'));
        }
        
    }
}
