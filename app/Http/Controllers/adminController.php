<?php

namespace App\Http\Controllers;

use Session;
use App\Cash;
use App\Sale;
use App\User;
use App\Admin;
use App\Order;
use App\Expense;
use Carbon\Carbon;
use App\GeneralOption;
use App\Returnproduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Spatie\Permission\Models\Role;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Permission;

class adminController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:Admin Permission')->only('index','create','edit','store','update','changeLoginStatus');
    }


    public function index(){
        
        $admins = Admin::where('id','!=',Auth::user()->id)->with('roles')->get();
        return view('admin.admininfo.index',compact('admins'));
    }

    public function create(){
        $roles = Role::all();
        return view('admin.admininfo.create',compact('roles'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|max:30|unique:admins',
            'email' => 'required|email|unique:admins',
            'phone' => 'required|unique:admins',
            'password' => 'required|confirmed|min:8|max:14',
            'role' => 'required',
        ]);

        $admin = new Admin;

        if($request->hasFile('signature')){
            //get form image
            $image = $request->file('signature');
            $slug = Str::slug($request['name']);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();

   
            //location for new image 
            $signature_location = public_path('uploads/admin/signature/'.$image_name);
            $original_location = public_path('uploads/admin/original/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->resize(339,null,function ($constraint) {$constraint->aspectRatio();})->save($signature_location);
            Image::make($image)->save($original_location);
            $admin->signature =  $image_name;
         }



        $admin->name = $request->name;
        $admin->adminname = Str::slug($request->name);
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->password = Hash::make($request->password);
        $admin->save();
        $admin->assignRole($request->role);
        Toastr::success('Admin Created Successfully','success');
        return redirect(route('admininfo.index'));
    }
    public function edit($id){
        $roles = Role::all();
        $admin = Admin::findOrFail($id);
        $permissions = Permission::all();
        return view('admin.admininfo.edit',compact('admin','roles','permissions'));
    }


    public function update(Request $request,$id){
        $this->validate($request,[
            'name' => 'required|max:30|unique:admins,name,'.$id,
            'email' => 'required|email|unique:admins,email,'.$id,
            'phone' => 'required|unique:admins,phone,'.$id,
            'role' => 'required',
            'signature' => 'image',
        ]);
    
        $admin = Admin::findOrFail($id);


        // $d_permit = [];
        // if($request->has('permissions')){
        //     foreach($request->permissions as $dp){
        //         $d_permit[] = $dp['name'];
        //     }   
        // }
        if($request->hasFile('signature')){
            //get form image
            $image = $request->file('signature');
            $slug = Str::slug($request['name']);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();

            //Delete Old Image
            $old_signature_location = public_path('uploads/admin/signature/'.$admin->image);
            $old_original_image_location = public_path('uploads/admin/original/'.$admin->image);

            
            if (File::exists($old_signature_location)) {
                File::delete($old_signature_location);
            }
            if (File::exists($old_original_image_location)) {
                File::delete($old_original_image_location);
            }
   
            //location for new image 
            $signature_location = public_path('uploads/admin/signature/'.$image_name);
            $original_location = public_path('uploads/admin/original/'.$image_name);
            //resize image  and upload temp 
            Image::make($image)->resize(339,null,function ($constraint) {$constraint->aspectRatio();})->save($signature_location);
            Image::make($image)->save($original_location);
            $admin->signature =  $image_name;
         }
         
        $admin->name = $request->name;
        //$admin->adminname = Str::slug($request->name);
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->save();
        $admin->syncRoles($request->role);

        if($request->has('permissions')){
            $admin->givePermissionTo($request->permissions);
        }
        Toastr::success('Admin Updated Successfully','success');
        return redirect()->back();
    }



    public function dashboard(){
        $today = now()->toDateString();
        $todays_order = Order::whereBetween('ordered_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('ordered_at', 'desc')->get();

        $todays_ecom_cash = Order::where('payment_status',1)->whereBetween('paymented_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('paymented_at', 'desc')->get();

        $todays_ecom_returns = Returnproduct::where('type','ecom')->whereBetween('returned_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('returned_at', 'desc')->get();
        $pending_cash = Order::where('payment_status',0)->where('cash','>',0)->get();

        $pending_orders = Order::where('order_status',0)->get();
        $pending_shipping = Order::where('payment_status',1)->where('shipping_status',0)->get();

        $current_month_order = Order::whereBetween('ordered_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');

        $current_year_order = Order::whereBetween('ordered_at', [Carbon::now()->startOfYear(), Carbon::now()])->sum('amount');

        $last_ten_orders = Order::where('order_status','!=',0)->take(10)->orderBy('id','desc')->get();

        return view('admin.dashboard',compact('pending_orders','pending_cash','todays_order','todays_ecom_cash','todays_ecom_returns','pending_shipping','current_month_order','current_year_order','last_ten_orders'));
    }

    public function orderdetails($id){
        return Order::with('product','user')->findOrFail($id);
    }

    public function inventorydashboard(){
        $general_opt = GeneralOption::first();
        $general_opt_value = json_decode($general_opt->options, true);
        $today = now()->toDateString();
        $todays_expense = Expense::whereBetween('expense_date', [$today." 00:00:00", $today." 23:59:59"])->orderBy('expense_date', 'desc')->get();
        $todays_pos_sales = Sale::whereBetween('sales_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('sales_at', 'desc')->get();
        $todays_pos_returns = Returnproduct::where('type','pos')->whereBetween('returned_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('returned_at', 'desc')->get();
        $todays_pos_cash = Cash::whereBetween('received_at', [$today." 00:00:00", $today." 23:59:59"])->orderBy('received_at', 'desc')->get();
        $pending_sales = Sale::where('sales_status',0)->get();
        $pending_cash = Cash::where('status',0)->get();
        $pending_delivery = Sale::where('sales_status',1)->where('delivery_status',0)->orderBy('id','DESC')->paginate(5);
        $pending_returns = Returnproduct::where('return_status',0)->get();
        $last_ten_dlv = Sale::where('delivery_status',1)->take(10)->orderBy('id','desc')->get();
        $current_month_sale = Sale::whereBetween('sales_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');
        $current_month_cash = Cash::whereBetween('received_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');
        $current_month_return = Returnproduct::whereBetween('returned_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');
        $current_month_expense = Expense::whereBetween('expense_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->sum('amount');
        
        return view('admin.inventorydashboard',compact('todays_pos_sales','todays_pos_cash','todays_pos_returns','pending_sales','general_opt_value','pending_cash','pending_returns','pending_delivery','last_ten_dlv','current_month_sale','current_month_cash','current_month_return','current_month_expense','todays_expense'));
    }

    public function inv_pendingcash($id){
        $admin = '';
        $cash = Cash::findOrFail($id);
        if(!empty($cash->approved_by)){
        $admin = Admin::findOrFail($cash->approved_by);
        }
        return view('pos.cash.show',compact('cash','admin'));
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


    public function changeLoginStatus($id){
        $admin = Admin::findOrFail($id);
        if($admin->status == 0){
            $admin->status = 1;
        }else{
            $admin->remember_token = null;
            $admin->status = 0;
        }
        $admin->save();
        Toastr::success('Admin Status Changed Successfully','success');
        return redirect()->back();
    }
}
