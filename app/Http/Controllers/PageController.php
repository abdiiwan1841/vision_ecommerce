<?php

namespace App\Http\Controllers;

use App\Page;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:Ecommerce Section');
    }
    
    public function index()
    {
        $pages = Page::all();
        return view('pages.index',compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('pages.create');
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
            'page_title' => 'required|max:40|unique:pages',
            'description' => 'required',
            'banner_image' => 'required|image',
        ]);

        $slug = Str::slug($request->page_title, '-');

        $page = new Page;


        if($request->hasFile('banner_image')){
            //get form image
            $image = $request->file('banner_image');
            $slug = Str::slug($request->page_title);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();
            //location for new image 

            $original_location = public_path('uploads/page/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->save($original_location);
            $page->banner_image =  $image_name;
         }


        $page->page_title = $request->page_title;
        $page->slug = substr($slug,0,20);
        $page->page_title = $request->page_title;
        $page->page_description = $request->description;
        $page->save();
        Toastr::success('Page Added Successfully', 'success');
        return redirect()->route('pages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('pages.edit',compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        
        $this->validate($request,[
            'page_title' => 'required|max:40|unique:pages,page_title,'.$page->id,
            'description' => 'required',
            'banner_image' => 'required|image',
        ]);

        $slug = Str::slug($request->page_title, '-');



        if($request->hasFile('banner_image')){
            //get form image
            $image = $request->file('banner_image');
            $slug = Str::slug($request->page_title);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();
            //location for new image 

            $old_original_image_location = public_path('uploads/page/'.$page->banner_image);
            if (File::exists($old_original_image_location)) {
                File::delete($old_original_image_location);
            }

            $original_location = public_path('uploads/page/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->save($original_location);
            $page->banner_image =  $image_name;
         }


        $page->page_title = $request->page_title;
        $page->slug = substr($slug,0,20);
        $page->page_title = $request->page_title;
        $page->page_description = $request->description;
        $page->save();
        Toastr::success('Page Updated Successfully', 'success');
        return redirect()->route('pages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();
        $old_original_image_location = public_path('uploads/page/'.$page->banner_image);
        if (File::exists($old_original_image_location)) {
            File::delete($old_original_image_location);
        }

        Toastr::success('Page Deleted Successfully', 'success');
        return redirect()->route('pages.index');
    }
}
