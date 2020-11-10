<?php

namespace App\Http\Controllers;
use Session;
use App\Category;
use Carbon\Carbon;
use App\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\CategoryStoreRequest;

class CategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:Product Section');
        $this->middleware('permission:Product Edit')->only('edit','update');
    }
    
    public function index()
    {
        $categories = Category::paginate(10);
        $subcategories = Subcategory::all();
        return view('categories.index',compact('categories','subcategories'));
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
    public function store(CategoryStoreRequest $request)
    {
    $category = new Category;
    if($request->hasFile('image')){
        //get form image
        $image = $request->file('image');
        $slug = Str::slug($request->category_name);
        $current_date = Carbon::now()->toDateString();
        //get unique name for image
        $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();
        //location for new image 
        $thumb_location = public_path('uploads/category/thumb/'.$image_name);
        $frontend_location = public_path('uploads/category/frontend/'.$image_name);
        $original_location = public_path('uploads/category/original/'.$image_name);
        //resize image for category and upload temp 
        Image::make($image)->resize(570,null,function ($constraint) {$constraint->aspectRatio();})->save($thumb_location);
        Image::make($image)->fit(570,320)->save($frontend_location);
        Image::make($image)->save($original_location);
        $category->image =  $image_name;
     }
     $category->category_name = $request->category_name;
     $category->save();
     Toastr::success('Category Added Successfully', 'success');
     return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return $category;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'edit_category_name' => 'required|unique:categories,category_name,'.$id,
            'edit_image' => 'image',
        ]);
        
        $category = Category::findOrFail ($id);

        if($request->hasFile('edit_image')){
            //get form image
            $image = $request->file('edit_image');
            $slug = Str::slug($request->edit_category_name);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();

            //Delete Old Image
            $old_thumb_location = public_path('uploads/category/thumb/'.$category->image);
            $old_frontend_image_location = public_path('uploads/category/frontend/'.$category->image);
            $old_original_image_location = public_path('uploads/category/original/'.$category->image);
            if (File::exists($old_thumb_location)) {
                File::delete($old_thumb_location);
            }
            if (File::exists($old_frontend_image_location)) {
                File::delete($old_frontend_image_location);
            }
            if (File::exists($old_original_image_location)) {
                File::delete($old_original_image_location);
            }

            //new location for new image 
            $thumb_location = public_path('uploads/category/thumb/'.$image_name);
            $frontend_location = public_path('uploads/category/frontend/'.$image_name);
            $original_location = public_path('uploads/category/original/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->resize(500,null,function ($constraint) {$constraint->aspectRatio();})->save($thumb_location);
            Image::make($image)->fit(570,320)->save($frontend_location);
            Image::make($image)->save($original_location);
            $category->image =  $image_name;
         }

        $category->category_name = $request->edit_category_name;
        $category->save();
        Toastr::success('Category Updated Successfully', 'success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return false;
    }
}
