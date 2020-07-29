<?php

namespace App\Http\Controllers\Frontend;

use App\Deal;
use App\Brand;
use App\Slider;
use App\Product;
use App\Category;
use Carbon\Carbon;
use App\Subcategory;
use App\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deal = Deal::with('dealproduct')->where('status',1)->first();
        if($deal != null){
            $deal_exp = $deal_expire_date = $deal->expired_at->toDateTimeString();
            $current_datetime = Carbon::now()->toDateTimeString();
            if(strtotime($current_datetime) > strtotime($deal_exp)){
                DB::table('deals')
                ->where('id', $deal->id)
                ->update(['status' => false]);
            }
        }

        $ad = Advertisement::first();
        $products = Product::where('type','ecom')->with('subcategory')->inRandomOrder()->take(10)->get();
        $random_product = Product::where('type','ecom')->inRandomOrder()->take(6)->get();
        $new_products = Product::where('type','ecom')->orderBy('id','desc')->take(2)->get();
        $sliders = Slider::get();
        $collections = Category::inRandomOrder()->take(6)->get();
        $category = Category::inRandomOrder()->take(3)->get();
        $subcategories = Subcategory::inRandomOrder()->take(12)->get();
        $brands = Brand::inRandomOrder()->take(9)->get();
        return view('frontend.home.index',compact('products','collections','random_product','sliders','category','deal','ad','subcategories','brands','new_products'));
    }

   
}
