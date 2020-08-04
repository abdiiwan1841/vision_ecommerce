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
use App\GeneralOption;
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

        $general_opt = GeneralOption::first();
        $general_opt_value = json_decode($general_opt->options, true);

        $ad = Advertisement::first();
        $products = Product::where('type','ecom')->with('subcategory')->inRandomOrder()->take(10)->get();
        $random_product = Product::where('type','ecom')->inRandomOrder()->take($general_opt_value['hot_pd_noi'])->get();
        $new_products = Product::where('type','ecom')->orderBy('id','desc')->take($general_opt_value['new_pd_noi'])->get();
        $sliders = Slider::get();
        $collections = Category::inRandomOrder()->take($general_opt_value['pd_collection_noi'])->get();
        $category = Category::inRandomOrder()->take(3)->get();
        $subcategories = Subcategory::inRandomOrder()->take($general_opt_value['pd_type_noi'])->get();
        $brands = Brand::inRandomOrder()->take($general_opt_value['pd_brands_noi'])->get();
        return view('frontend.home.index',compact('products','collections','random_product','sliders','category','deal','ad','subcategories','brands','new_products'));
    }

   
}
