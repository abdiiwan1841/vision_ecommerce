<?php

namespace App\Http\Controllers;

use Session;
use App\User;
use App\Cash;
use App\Sale;
use App\Admin;
use App\Order;
use Carbon\Carbon;
use App\Returnproduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class adminController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }

    public function dashboard(){
        $today = now()->toDateString();
        $todays_order = Order::whereBetween('ordered_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('ordered_at', 'desc')->get();

        $todays_ecom_cash = Order::where('payment_status',1)->whereBetween('paymented_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('paymented_at', 'desc')->get();

        $todays_ecom_returns = Returnproduct::where('type','ecom')->whereBetween('returned_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('returned_at', 'desc')->get();

        $pending_orders = Order::where('order_status',0)->get();
        $pending_shipping = Order::where('order_status',1)->where('shipping_status',0)->get();

        return view('admin.dashboard',compact('pending_orders','todays_order','todays_ecom_cash','todays_ecom_returns','pending_shipping'));
    }

    public function inventorydashboard(){
        $today = now()->toDateString();


        $todays_pos_sales = Sale::whereBetween('sales_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('sales_at', 'desc')->get();

        $todays_pos_returns = Returnproduct::where('type','pos')->whereBetween('returned_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('returned_at', 'desc')->get();

        $todays_pos_cash = Cash::whereBetween('received_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('received_at', 'desc')->get();

        return view('admin.inventorydashboard',compact('todays_pos_sales','todays_pos_cash','todays_pos_returns'));
    }

    public function changepassword(){
        return view('admin.changepassword');
    }

    public function passUpdate(Request $request){
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
        return redirect(route('admin.login'));
    }

    public function  profile(){
        return view('admin.profile.index');
    }
    public function  editprofile(){
        return view('admin.profile.edit');
    }

    public function updateprofile(Request $request){
        $this->validate($request,[
            'name' => 'required|max:30',
            'email' => 'required|email',
            'phone' => 'required|max:25',
            'image' => 'image',
        ]);

        $admin = Admin::findOrFail(Auth::user()->id);

        if($request->hasFile('image')){
            //get form image
            $image = $request->file('image');
            $slug = Str::slug($request->name);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();
            
       

        if($admin->image != 'default.png'){
            $old_thumb_location = public_path('uploads/user/thumb/'.$admin->image);
            $old_original_image_location = public_path('uploads/user/'.$admin->image);
     
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
            $admin->image =  $image_name;
         }

        
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->save();

        Toastr::success('Profile Updated Successfully','success');
        return redirect(route('admin.profile'));



    }
}
