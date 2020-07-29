<?php

namespace App\Http\Controllers;
use Session;
use App\Slider;
use Carbon\Carbon;
use App\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\SliderStoreRequest;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::paginate(10);
        $subcategories = Subcategory::all();
        return view('slider.index',compact('sliders','subcategories'));
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
    public function store(SliderStoreRequest $request)
    {
        $slider = new Slider;
    if($request->hasFile('image')){
        //get form image
        $image = $request->file('image');
        $slug = Str::slug($request['title']);
        $current_date = Carbon::now()->toDateString();
        //get unique name for image
        $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();
        //location for new image 
        $thumb_location = public_path('uploads/sliders/thumb/'.$image_name);
        $sacled_location = public_path('uploads/sliders/scaled/'.$image_name);
        $original_location = public_path('uploads/sliders/original/'.$image_name);
        //resize image for category and upload temp 
        Image::make($image)->resize(500,null,function ($constraint) {$constraint->aspectRatio();})->save($thumb_location);
        Image::make($image)->fit(1366,550)->save($sacled_location);
        Image::make($image)->save($original_location);
        $slider->image =  $image_name;
     }
     $slider->title = $request['title'];
     $slider->title_color = $request['title_color'];
     $slider->description = $request['description'];
     $slider->description_color = $request['description_color'];
     $slider->button_text = $request['button_text'];
     $slider->button_link = $request['button_link'];
     $slider->button_color = $request['color'];
     if($request['box_status'] != null){
        $slider->box_status = $request['box_status'];
        $slider->box_text = $request['box_text'];
        $slider->box_color = $request['discount_box_color'];
     }
     $slider->save();
     Toastr::success('Slider Added Successfully', 'success');
     return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return $slider;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    
        $this->validate($request,[
            'title' => 'required|max:50|unique:sliders,title,'.$id,
            'title_color' => 'required',
            'description' => 'max:250',
            'description_color' => 'required',
            'image' => 'image',
            'color' => 'required',
            'button_text' => 'required',
            'button_link' => 'required',
        ]);



        $slider = Slider::findOrFail($id);

        if($request->hasFile('image')){
            //get form image
            $image = $request->file('image');
            $slug = Str::slug($request['title']);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();

            //Delete Old Image
            $old_thumb_location = public_path('uploads/sliders/thumb/'.$slider->image);
            $old_original_image_location = public_path('uploads/sliders/original/'.$slider->image);
            $old_sacled_location = public_path('uploads/sliders/scaled/'.$slider->image);

            
            if (File::exists($old_thumb_location)) {
                File::delete($old_thumb_location);
            }
            if (File::exists($old_original_image_location)) {
                File::delete($old_original_image_location);
            }
            if (File::exists($old_sacled_location)) {
                File::delete($old_sacled_location);
            }
            //location for new image 
            $thumb_location = public_path('uploads/sliders/thumb/'.$image_name);
            $scaled_location = public_path('uploads/sliders/scaled/'.$image_name);
            $original_location = public_path('uploads/sliders/original/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->resize(500,null,function ($constraint) {$constraint->aspectRatio();})->save($thumb_location);
            Image::make($image)->fit(1366,550)->save($scaled_location);
            Image::make($image)->save($original_location);
            $slider->image =  $image_name;
         }
         $slider->title = $request['title'];
         $slider->title_color = $request['title_color'];
         $slider->description = $request['description'];
         $slider->description_color = $request['description_color'];
         $slider->button_text = $request['button_text'];
         $slider->button_link = $request['button_link'];
         $slider->button_color = $request['color'];
         if($request['box_status'] == null){
            $slider->box_status = NULL;
            $slider->box_text = NULL;
            $slider->box_color = NULL;
         }else{
            $slider->box_status = $request['box_status'];
            $slider->box_text = $request['box_text'];
            $slider->box_color = $request['discount_box_color'];
         }
         $slider->save();
         Toastr::success('Slider Updated Successfully', 'success');
         return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);

        $old_thumb_location = public_path('uploads/sliders/thumb/'.$slider->image);
        $old_original_image_location = public_path('uploads/sliders/original/'.$slider->image);
        $sacled_location = public_path('uploads/sliders/scaled/'.$slider->image);

        if (File::exists($old_thumb_location)) {
            File::delete($old_thumb_location);
        }
        if (File::exists($old_original_image_location)) {
            File::delete($old_original_image_location);
        }
        if (File::exists($sacled_location)) {
            File::delete($sacled_location);
        }
        $slider->delete();
        Session::flash('delete_success', 'Your Slider Has been Deleted Successfully'); 
        return redirect()->back();
    }
}
