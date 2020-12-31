<?php

namespace App\Http\Controllers;
use Session;
use Carbon\Carbon;
use App\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\SubcategoryStoreRequest;

class SubcategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:Product Section');
        $this->middleware('permission:Product Edit')->only('edit','update');
    }

    
    public function index()
    {
        $subcategories = Subcategory::orderBy('id','DESC')->paginate(10);
        return view('subcategory.index',compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubcategoryStoreRequest $request)
    {
        $subcategory = new Subcategory;


        if($request->hasFile('subcategory_image')){
            //get form image
            $image = $request->file('subcategory_image');
            $slug = Str::slug($request->subcategory_name);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();
            //location for new image 
  
            $frontend_location = public_path('uploads/product_type/frontend/'.$image_name);
            $original_location = public_path('uploads/product_type/original/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->fit(285,160)->save($frontend_location);
            Image::make($image)->save($original_location);
            $subcategory->image =  $image_name;
         }

        $subcategory->subcategory_name = $request->subcategory_name;
        $subcategory->save();
        return 'Product Type Stored Successfully';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Subcategory $subcategory)
    {
        return Subcategory::findOrFail($subcategory->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'subcategory_name' => 'required|unique:subcategories,subcategory_name,'.$id,
            'subcategory_image' => 'image',
        ]);
        $subcategory = Subcategory::find($id);


        if($request->hasFile('subcategory_image')){
            //get form image
            $image = $request->file('subcategory_image');
            $slug = Str::slug($request->subcategory_name);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();


               //Delete Old Image

               $old_frontend_image_location = public_path('uploads/product_type/frontend/'.$subcategory->image);
               $old_original_image_location = public_path('uploads/product_type/original/'.$subcategory->image);

               if (File::exists($old_frontend_image_location)) {
                   File::delete($old_frontend_image_location);
               }
               if (File::exists($old_original_image_location)) {
                   File::delete($old_original_image_location);
               }



            //location for new image 
  
            $frontend_location = public_path('uploads/product_type/frontend/'.$image_name);
            $original_location = public_path('uploads/product_type/original/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->fit(285,160)->save($frontend_location);
            Image::make($image)->save($original_location);
            $subcategory->image =  $image_name;
         }


        $subcategory->subcategory_name = $request->subcategory_name;
        $subcategory->save();
        return 'Product Types Updated Successfully';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
