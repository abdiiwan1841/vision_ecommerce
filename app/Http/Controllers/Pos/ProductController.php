<?php

namespace App\Http\Controllers\Pos;
use App\Http\Controllers\Controller;
use Session;
use App\Size;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;



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
 
        $sizes = Size::get();
        $products = Product::where('type','pos')->with('size')->get();
        return view('pos.products.index',compact('products','sizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sizes = Size::get();
        return view('pos.products.create',compact('sizes'));
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
            'product_name' => 'required|unique:products',
            'image' => 'image',
            'price' => 'required|numeric',
            'size' => 'required',
    ]);
    $product = new Product;
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
        Image::make($image)->fit(270,330)->save($thumb_location);
        Image::make($image)->save($original_location);
        $product->image =  $image_name;
     }
     $product->product_name = $request['product_name'];
     $product->price = $request['price'];
     $product->category_id = 1;
     $product->subcategory_id = 1;
     $product->brand_id = 1;
     $product->size_id = $request['size'];
     $product->type = 'pos';
     $product->save();
     Toastr::success('Product Added Successfully', 'success');
     return redirect(route('posproducts.index'));


    }

    public function show($id)
    {
        $product = Product::with('subcategory','brand','tags')->findOrFail($id);
        return view('pos.products.show',compact('product'));
    }

    public function edit($id)
    {
        $sizes = Size::get();
        $product = Product::where('type','pos')->findOrFail($id);
        return view('pos.products.edit',compact('sizes','product'));
    }


    public function update(Request $request,$id)
    {
        $this->validate($request,[
                'product_name' => 'required|unique:products,product_name,'.$id,
                'image' => 'image',
                'price' => 'required|numeric',
                'size' => 'required',
        ]);
        $product = Product::findOrFail($id);

        if($request->hasFile('image')){
            //get form image
            $image = $request->file('image');
            $slug = Str::slug($request->product_name);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();


            if($product->image != 'product.jpg'){
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
            }


            //new location for new image 
            $tiny_location = public_path('uploads/products/tiny/'.$image_name);
            $thumb_location = public_path('uploads/products/thumb/'.$image_name);
            $original_location = public_path('uploads/products/original/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->fit(70,70)->save($tiny_location);
            Image::make($image)->fit(270,330)->save($thumb_location);
            Image::make($image)->save($original_location);
            $product->image =  $image_name;
         }

        $product->product_name = $request['product_name'];
        if($product->price < $request['price']){
            $product->price = $request['price'];
            $product->discount_price = NULL;
        }elseif($product->price > $request['price']){
            $product->discount_price = $request['price'];
        }
        $product->size_id = $request['size'];
        $product->save();

        Toastr::success('Product Updated Successfully', 'success');
        return redirect(route('posproducts.index'));
    }


    public function destroy($id)
    {
        $product = Product::find($id);
        $product->deleted_at = now();
        $product->save();
        Session::flash('delete_success', 'Your Product Has been Deleted Successfully'); 
        return redirect()->back();
    }

    public function transfertoecom(Request $request,$id){
        $this->validate($request,[
            'type' => 'required',
        ]);

        $product = Product::findOrFail($id);
        $product->type = $request->type;
        $product->save();
        Session::flash('success','Product Successfully Transfered To Ecommerce Module.You Have to Check the product image, category, size etc then hit the update button');
        return redirect()->route('products.edit',$id);

    }
}
