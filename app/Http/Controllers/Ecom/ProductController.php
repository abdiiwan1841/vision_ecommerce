<?php

namespace App\Http\Controllers\Ecom;
use App\Http\Controllers\Controller;
use Session;
use App\Size;
use App\Tags;
use App\Brand;
use App\Product;
use App\Category;
use Carbon\Carbon;
use App\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductStoreRequest;
use Symfony\Component\Console\Input\Input;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function index()
    {
        $categories = Category::all();
        $subcategory = Subcategory::all();
        $tags = Tags::all();
        $brands = Brand::all();
        $sizes = Size::get();
        $products = Product::where('type','ecom')->with('category','subcategory','brand','tags')->get();
        return view('ecom.products.index',compact('products','categories','subcategory', 'tags', 'brands','sizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $subcategory = Subcategory::all();
        $tags = Tags::all();
        $brands = Brand::all();
        $sizes = Size::get();
        return view('ecom.products.create',compact('categories','subcategory', 'tags', 'brands','sizes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
    $product = new Product;

    //Product Image Upload

    if($request->hasFile('image')){
        //get form image
        $image = $request->file('image');
        $slug = Str::slug($request->product_name);
        $current_date = Carbon::now()->toDateString();
        //get unique name for image
        $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();
        //location for new image 
        $tiny_location = public_path('uploads/products/tiny/'.$image_name);
        $thumb_location = public_path('uploads/products/thumb/'.$image_name);
        $original_location = public_path('uploads/products/original/'.$image_name);
        //resize image for category and upload temp 
        Image::make($image)->fit(70,70)->save($tiny_location);
        Image::make($image)->fit(540,660)->save($thumb_location);
        Image::make($image)->save($original_location);
        $product->image =  $image_name;
     }


     //Gallery Image Upload
     if($request->hasfile('gallery_image')){
        foreach($request->file('gallery_image') as $key => $file){
            $gallery_image_name = $key.rand(1,1000).time().'.'.$file->extension();
            $thumb_location = public_path('uploads/gallery/thumb/'.$gallery_image_name);
            $gal_location = public_path('uploads/gallery/'.$gallery_image_name);
            Image::make($file)->fit(105,105)->save($thumb_location);
            Image::make($file)->save($gal_location);
            $gallery_image_data[] = $gallery_image_name;  
        }
        $product->gallery_image = json_encode($gallery_image_data);
     }
     $product->product_name = $request['product_name'];
     $product->price = $request['price'];
     $product->current_price = $request['price'];
     $product->category_id = $request['category'];
     $product->subcategory_id = $request['subcategory'];
     $product->brand_id = $request['brand'];
     $product->description = $request['description'];
     $product->size_id = $request['size'];
     $product->type = 'ecom';
     $product->save();
     $product->tags()->attach($request['tags']);

     $productinfo = Product::where('type','ecom')->get();
     $suggestions = [];
     foreach($productinfo as $info){
         $suggestions[] = ['value' => $info->product_name, 'data' => $info->id];
     }

     Storage::disk('public')->put('product.json', "var productJSON=".json_encode($suggestions));
     Toastr::success('Product Added Successfully', 'success');
     return redirect()->route('products.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('subcategory','brand','tags')->findOrFail($id);
        return view('ecom.products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::where('type','ecom')->with('tags')->findOrFail($id);
        $categories = Category::all();
        $subcategory = Subcategory::all();
        $tags = Tags::all();
        $brands = Brand::all();
        $sizes = Size::get();
        return view('ecom.products.edit',compact('product','categories','subcategory','tags','brands','sizes'));
    }


    public function update(Request $request,$id)
    {
  
        $this->validate($request,[
            'product_name' => 'required|unique:products,product_name,'.$id,
            'price' => 'required|numeric',
            'discount_price' => 'numeric',
            'subcategory' => 'required|integer',
            'category' => 'required|integer',
            'brand' => 'required|integer',
            'tags' => 'required',
            'description' => 'required',
            'size' => 'required',
            'image' => 'image',
        ]);

        $product = Product::findOrFail($id);

        if($request->hasFile('image')){
            //get form image
            $image = $request->file('image');
            $slug = Str::slug($request->product_name);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();

            //Delete Old Image
            $old_tiny_location = public_path('uploads/products/tiny/'.$product->image);
            $old_thumb_location = public_path('uploads/products/thumb/'.$product->image);
            $old_original_image_location = public_path('uploads/products/original/'.$product->image);
            if (File::exists($old_tiny_location)) {
                File::delete($old_tiny_location);
            }
            if (File::exists($old_thumb_location)) {
                File::delete($old_thumb_location);
            }
            if (File::exists($old_original_image_location)) {
                File::delete($old_original_image_location);
            }

            //new location for new image 
            $tiny_location = public_path('uploads/products/tiny/'.$image_name);
            $thumb_location = public_path('uploads/products/thumb/'.$image_name);
            $original_location = public_path('uploads/products/original/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->fit(70,70)->save($tiny_location);
            Image::make($image)->fit(540,660)->save($thumb_location);
            Image::make($image)->save($original_location);
            $product->image =  $image_name;
         }

        if(!empty($product->gallery_image)){
        $old_gallery_img_list  = json_decode($product->gallery_image);
        }
        
          //Gallery Image Upload
        if($request->hasfile('gallery_image')){
            foreach($request->file('gallery_image') as $key => $file){
                $gallery_image_name = $key.rand(1,1000).time().'.'.$file->extension();
                $thumb_location = public_path('uploads/gallery/thumb/'.$gallery_image_name);
                $gal_location = public_path('uploads/gallery/'.$gallery_image_name);
                Image::make($file)->fit(105,105)->save($thumb_location);
                Image::make($file)->save($gal_location);
                $gallery_image_data[] = $gallery_image_name;  
            }
            if(!empty($product->gallery_image)){
            $updated_gallery_list = array_merge($old_gallery_img_list,$gallery_image_data);
            $product->gallery_image = json_encode($updated_gallery_list);
            }else{
                $product->gallery_image = json_encode($gallery_image_data);
            }
        }

        $product->product_name = $request['product_name'];



        if($request->price >=  $product->price){
            $product->price = $request->price;
            $product->discount_price = NULL;
            $product->current_price = $request->price;
        }elseif($request->price < $product->price){
            $product->discount_price = $request->price;
            $product->current_price = $request->price;
        }

        $product->description = $request['description'];
        $product->category_id = $request['category'];
        $product->subcategory_id = $request['subcategory'];
        $product->brand_id = $request['brand'];
        $product->size_id = $request['size'];
        $product->type = 'ecom';
        $product->save();

        $product->tags()->sync($request['tags']);

        $productinfo = Product::where('type','ecom')->get();
        $suggestions = [];
        foreach($productinfo as $info){
            $suggestions[] = ['value' => $info->product_name, 'data' => $info->id];
        }

        Storage::disk('public')->put('product.json', "var productJSON=".json_encode($suggestions));
        Toastr::success('Product Updated Successfully', 'success');
        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->deleted_at = now();
        $product->save();
        Session::flash('delete_success', 'Your Product Has been Deleted Successfully'); 
        return redirect()->back();
    }

    public function removegalleryimage(Request $request,$id){
        $this->validate($request,[
            'gal_image' => 'required',
        ]);
        $product = Product::findOrFail($id);
        $old_image_list = json_decode($product->gallery_image);
        $to_remove = array_search($request->gal_image, $old_image_list);
        unset($old_image_list[$to_remove]);
        $product->gallery_image = $old_image_list;
        $product->save();




         //Delete Old Image
         $old_thumb_image_location = public_path('uploads/gallery/thumb/'.$request->gal_image);
         $old_original_image_location = public_path('uploads/gallery/'.$request->gal_image);
         if (File::exists($old_thumb_image_location)) {
             File::delete($old_thumb_image_location);
         }

         if (File::exists($old_original_image_location)) {
             File::delete($old_original_image_location);
         }

         Toastr::success('Gallery Image Deleted Successfully', 'success');
         return redirect()->back();

    }

    public function transfertoinventory(Request $request,$id){
        $this->validate($request,[
            'type' => 'required',
        ]);

        $product = Product::findOrFail($id);
        $product->type = $request->type;
        $product->save();
        Session::flash('success','Product Successfully Transfered To Inventory Module');
        return redirect()->route('posproducts.edit',$id);

    }
}
