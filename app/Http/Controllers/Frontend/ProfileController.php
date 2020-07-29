<?php

namespace App\Http\Controllers\Frontend;

use Session;
use App\User;
use App\Division;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;


class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function show(){
        $user = User::with('district','area')->findOrFail(Auth::user()->id);
        return view('frontend.profile.show',compact('user'));
    }

    public function editprofile(){
        $divisions = Division::all();
        return view('frontend.profile.edit',compact('divisions'));
    }

    public function update(Request $request){

        $this->validate($request,[
            'name' =>  'required|string|max:30',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'phone' => 'required|unique:users,phone,'.Auth::user()->id,
            'division' => 'required|numeric',
            'district' => 'required|numeric',
            'area' => 'required|numeric',
            'address' => 'required|max:500',
            'image' => 'image',
        ]);
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        
        if($request->hasFile('image')){
            //get form image
            $image = $request->file('image');
            $slug = Str::slug($request->name);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();
            

        if($user->image != 'user.jpg'){
            $old_thumb_location = public_path('uploads/user/thumb/'.$user->image);
            $old_original_image_location = public_path('uploads/user/'.$user->image);
     
            if (File::exists($old_thumb_location)) {
                File::delete($old_thumb_location);
            }
            if (File::exists($old_original_image_location)) {
                File::delete($old_original_image_location);
            }
           }
           
           
           
            //location for new image 
            $thumb_location = public_path('uploads/user/thumb/'.$image_name);
            $original_location = public_path('uploads/user/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->fit(150,150)->save($thumb_location);
            Image::make($image)->save($original_location);
            $user->image =  $image_name;
         }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->division_id = $request->division;
        $user->district_id = $request->district;
        $user->area_id = $request->area;
        $user->address = $request->address;
        $user->save();

        Session::flash('success','Profile Updated Successfully');
        return redirect(route('profile.show'));
    }

    public function changepassword(){

        return view('frontend.profile.changepassword');
        
    }

    public function passupdate(Request $request){
        $this->validate($request,[
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);



        if (!(Hash::check($request->get('old_password'), Auth::user()->password))) {
            Session::flash('old_password','Your current password does not matches with the password you provided. Please try again.');
            return redirect()->back()->withInput();
        }

        if(strcmp($request->get('old_password'), $request->get('password')) == 0){
            Session::flash('password','New Password cannot be same as your current password. Please choose a different password.');
            return redirect()->back()->withInput();
        }


        //Change Password
        $user = Auth::user();
        $user->password = Hash::make($request['password']);
        $user->save();
        Session::flash('success','Password Changed Successfull !  Please Login');
        Auth::logout();
        return redirect(route('login'));
    }
}
