<?php

namespace App\Http\Controllers\Frontend;


use App\Tags;
use App\Brand;
use App\Product;
use App\Category;
use App\Headertop;
use App\Subcategory;
use Illuminate\Http\Request;
use Harimayco\Menu\Facades\Menu;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;


class ShoppageController extends Controller
{
    public function index()
    {
        $products = Product::where('type','ecom')->with('subcategory')->paginate(12);
        $categories = Category::latest()->limit(5)->get();
        $tags = Tags::latest()->limit(10)->get();
        $brands = Brand::latest()->limit(10)->get();
        $minprice = Product::where('type','ecom')->min('current_price');
        $maxprice = Product::where('type','ecom')->max('current_price');

        return view('frontend.shop.index',compact('products','categories','tags','brands','minprice','maxprice'));
    }

    public function categoryproduct($id){
        $products = Product::where('type','ecom')->where('category_id',$id)->with('subcategory')->paginate(12);
        $categories = Category::latest()->limit(5)->get();
        $tags = Tags::latest()->limit(10)->get();
        $brands = Brand::latest()->limit(10)->get();

        $minprice = Product::where('type','ecom')->min('current_price');
        $maxprice = Product::where('type','ecom')->max('current_price');
        $cat_info = Category::findOrFail($id);
        return view('frontend.shop.categoryproduct.index',compact('products','categories','tags','brands','minprice','maxprice','cat_info'));
    }

    public function subcategoryproduct($id){
        $products = Product::where('type','ecom')->where('subcategory_id',$id)->with('subcategory')->paginate(12);
        $categories = Category::latest()->limit(5)->get();
        $tags = Tags::latest()->limit(10)->get();
        $brands = Brand::latest()->limit(10)->get();

        $minprice = Product::where('type','ecom')->min('current_price');
        $maxprice = Product::where('type','ecom')->max('current_price');
        $subcat_info = Subcategory::findOrFail($id);
        return view('frontend.shop.index',compact('products','categories','tags','brands','minprice','maxprice','subcat_info'));
    }

    public function brandproduct($id){
        $products = Product::where('type','ecom')->where('brand_id',$id)->with('brand')->paginate(12);
        $categories = Category::latest()->limit(5)->get();
        $tags = Tags::latest()->limit(10)->get();
        $brands = Brand::latest()->limit(10)->get();

        $minprice = Product::where('type','ecom')->min('current_price');
        $maxprice = Product::where('type','ecom')->max('current_price');
        $subcat_info = Subcategory::findOrFail($id);
        return view('frontend.shop.index',compact('products','categories','tags','brands','minprice','maxprice','subcat_info'));
    }


    public function tagproduct($id){

        $products = Product::where('type','ecom')->whereHas('tags',function ($q) use($id) {
            $q->where('tags_id', $id);
        })->paginate(12);
        $categories = Category::latest()->limit(5)->get();
        $tags = Tags::latest()->limit(10)->get();
        $brands = Brand::latest()->limit(10)->get();
        $minprice = Product::where('type','ecom')->min('current_price');
        $maxprice = Product::where('type','ecom')->max('current_price');
        $tag_info = Tags::findOrFail($id);
        return view('frontend.shop.index',compact('products','categories','tags','brands','minprice','maxprice','tag_info'));
    }

    public function filterbyprice(Request $request){
        if($request->has('brand')){
            $products = Product::where('type','ecom')->whereIn('brand_id', $request['brand'])->whereBetween('current_price', [$request->minamount, $request->maxamount])->with('subcategory')->orderBy('current_price', 'ASC')->get();
            foreach($request->brand as $brand_id){
                $brand_list[] = Brand::findOrFail($brand_id);
            }
        }else{
            $products = Product::where('type','ecom')->whereBetween('current_price', [$request->minamount, $request->maxamount])->with('subcategory')->orderBy('current_price', 'ASC')->paginate(10);
        }
        
        $categories = Category::latest()->limit(5)->get();
        $tags = Tags::latest()->limit(10)->get();
        $brands = Brand::latest()->limit(10)->get();
        $minprice = Product::where('type','ecom')->min('current_price');
        $maxprice = Product::where('type','ecom')->max('current_price');

        if($request->has('brand')){
            return view('frontend.shop.filter',compact('products','categories','tags','brands','minprice','maxprice','request', 'brand_list'));
        }else{
        return view('frontend.shop.filter',compact('products','categories','tags','brands','minprice','maxprice','request'));
        }

        
    }

    public function redirectmain(){
        return redirect(route('shoppage.index'));
    }
    
 

    public function success(){
        $headerTop = Headertop::all()->first();
        $social =  json_decode($headerTop->social, true);
        $top_menu = Menu::getByName('top_menu');
        return view('success.order_success',compact('headerTop','social','top_menu'));
    }


}
