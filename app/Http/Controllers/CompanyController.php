<?php

namespace App\Http\Controllers;

use App\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CompanyController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }
    
    public function index()
    {
        $company = Company::first();
        return view('company.index',compact('company'));
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        $socials = json_decode($company->social, true);
        return  view('company.edit',compact('company','socials'));
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'company_name' => 'required|Max:30',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required|Max: 100',
            'bin' => 'required',
            'facebook' => 'required',
            'twitter' => 'required',
            'linkedin' => 'required',
            'pinterest' => 'required',
            'image' => 'image',
            'favicon' => 'image',
        ]);

        $social_arr = ['facebook' => [$request->facebook,$request->visibility1], 'twitter' => [$request->twitter,$request->visibility2], 'pinterest' => [$request->pinterest,$request->visibility4],'linkedin' => [$request->linkedin,$request->visibility3]];
       
        $company = Company::findOrFail($id);

        if($request->hasFile('image')){
            //get form image
            $image = $request->file('image');
            $slug = 'logo_'.rand(1,100);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."_".$current_date.".".$image->getClientOriginalExtension();

            //Delete Old Image
            $old_cropped_location = public_path('uploads/logo/cropped/'.$company->image);
            $old_original_image_location = public_path('uploads/logo/original/'.$company->image);
            
            if (File::exists($old_cropped_location)) {
                File::delete($old_cropped_location);
            }
            if (File::exists($old_original_image_location)) {
                File::delete($old_original_image_location);
            }

            //location for new image 
            $cropped_location = public_path('uploads/logo/cropped/'.$image_name);
            $original_image_location = public_path('uploads/logo/original/'.$image_name);
            //resize image for category and upload temp 

            Image::make($image)->fit(183,66)->save($cropped_location);
            Image::make($image)->save($original_image_location);
            $company->logo =  $image_name;
         }

         if($request->hasFile('favicon')){
            //get form image
            $favicon = $request->file('favicon');
            $slug = 'favicon_'.rand(1,100);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $favicon_name = $slug."_".$current_date.".".$favicon->getClientOriginalExtension();

            //Delete Old Image
            $old_favicon_cropped_location = public_path('uploads/favicon/cropped/'.$company->image);
            $old_original_favicon_location = public_path('uploads/favicon/original/'.$company->image);
            
            if (File::exists($old_favicon_cropped_location)) {
                File::delete($old_favicon_cropped_location);
            }
            if (File::exists($old_original_favicon_location)) {
                File::delete($old_original_favicon_location);
            }

            //location for new image 
            $cropped_location = public_path('uploads/favicon/cropped/'.$favicon_name);
            $original_favicon_location = public_path('uploads/favicon/original/'.$favicon_name);
            //resize image for category and upload temp 

            Image::make($favicon)->fit(100,100)->save($cropped_location);
            Image::make($favicon)->save($original_favicon_location);
            $company->favicon =  $favicon_name;
         }

        
        $company->company_name = $request->company_name;
        $company->email = $request->email;
        $company->phone = $request->phone;
        $company->address = $request->address;
        $company->bin = $request->bin;
        $company->social = $social_arr;
        $company->map_embed = $request->map_embed;
        $company->save();

        Toastr::success('Company Information Updated Successfully', 'success');
        return redirect()->route('company.index');
    }


    public function destroy(Company $company)
    {
        //
    }
}
