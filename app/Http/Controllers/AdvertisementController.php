<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Advertisement;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class AdvertisementController extends Controller
{
    public function index(){
        $adv = Advertisement::first();
        return view('advertisement.index',compact('adv'));
    }

    public function edit($id){
        $adv = Advertisement::findOrFail($id);
        return view('advertisement.edit',compact('adv'));
    }

    public function update(Request $request,$id){
        $this->validate($request,[
            'title' => 'required|max:20',
            'image' => 'image',
            'button_text' => 'required: Max:15',
            'button_link' => 'required',
        ]);
        $adv = Advertisement::findOrFail($id);

        if($request->hasFile('image')){
            //get form image
            $image = $request->file('image');
            $slug = 'ad_'.rand(1,100);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."_".$current_date.".".$image->getClientOriginalExtension();

            //Delete Old Image
            $old_cropped_image_location = public_path('uploads/ad/cropped/'.$adv->image);
            $old_original_image_location = public_path('uploads/ad/original/'.$adv->image);
            
            if (File::exists($old_cropped_image_location)) {
                File::delete($old_cropped_image_location);
            }
            if (File::exists($old_original_image_location)) {
                File::delete($old_original_image_location);
            }

            //location for new image 
            $cropped_location = public_path('uploads/ad/cropped/'.$image_name);
            $original_image_location = public_path('uploads/ad/original/'.$image_name);
            //resize image for category and upload temp 

            Image::make($image)->fit(470,620)->save($cropped_location);
            Image::make($image)->save($original_image_location);
            $adv->image =  $image_name;
         }
        $adv->title = $request->title;
        $adv->button_text = $request->button_text;
        $adv->button_link = $request->button_link;
        $adv->save();

        Toastr::success('Advertisement Updated Successfully', 'success');
        return redirect()->route('advertisement.index');
    }
}
