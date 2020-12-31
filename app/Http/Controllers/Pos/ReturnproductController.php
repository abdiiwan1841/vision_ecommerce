<?php

namespace App\Http\Controllers\Pos;

use PDF;
use App\User;
use App\Admin;
use App\Charge;
use App\Product;
use Carbon\Carbon;
use App\GeneralOption;
use App\Returnproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class ReturnproductController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:Inventory Returns');
        $this->middleware('permission:Inventory Edit Returns')->only('edit','update');
        $this->middleware('permission:Inventory Approve Returns')->only('approve');
        $this->middleware('permission:Inventory Cancel Returns')->only('destroy');
    }

    
    public function index()
    {
        $returns = Returnproduct::withTrashed('user','prouduct')->take(10)->orderBy('id', 'desc')->get();
        return view('pos.return.index',compact('returns'));
    }

    public function result(Request $request){
        $this->validate($request,[
            'start' => 'required|date',
            'end' => 'required|date',
        ]);


        $returns = Returnproduct::where('type','pos')->withTrashed('user','prouduct')->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('returned_at', 'asc')->get();
        return view('pos.return.result',compact('returns','request'));
    }



    public function create()
    {
        $charge = Charge::first();
        $users = User::where('user_type','pos')->get();
        $products = Product::get();
        return view('pos.return.create',compact('products','users','charge'));
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
            'return_date' => 'required|date',
            'user_id' => 'required|numeric',
            'discount' => 'required|numeric',
            'carrying_and_loading' => 'required|numeric',
            'product' => 'required',
        ]);


         //Amount calculation
         $products = json_decode($request->product);
        
        
        $pdinfo = "";
        $amount = [];
        foreach($products as  $item){
            $pdinfo .= $item->o_name." = ".$item->count.",";
            $amount[] = ($item->count)*($item->price);
        }
        $netamount = array_sum($amount);
        $amount_total = ($netamount+$request->carrying_and_loading)-($request->discount);


        $return = new Returnproduct;
        $return->user_id = $request->user_id;
        $return->discount = $request->discount;
        $return->carrying_and_loading = $request->carrying_and_loading;
        $return->amount = $amount_total;
        $return->returned_at = $request->return_date." ".Carbon::now()->toTimeString();
        $return->returned_by = Auth::user()->name;
        $return->type = 'pos';
        $return->save();

        
        $products = json_decode($request->product);
        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['returnproduct_id' =>$return->id, 'product_id' => $product->id,'qty' => $product->count,'price' => $product->price, 'user_id' => $request->user_id,'returned_at' => $request->return_date." ".Carbon::now()->toTimeString()];   
        }
        $return->product()->attach($product_info);

        // $url = "http://66.45.237.70/api.php";
        // $number="01700817934";
        // $text="New Return Invoice, Date: ".$return->returned_at->format('d-m-Y')." Return Invoice ID:# ".$return->id." Customer: ".$return->user->name.",  ".$return->user->address.", Amount: ".$return->amount." Prepared By:".Auth::user()->name." .Please Review the Return Invoice,Thanks";

        // $text2= $return->user->name.", your Return invoice is created, Date: ".$return->returned_at->format('d-m-Y')." Return Invoice ID:# ".$return->id." Product: ".$pdinfo.", Amount: ".$return->amount." Prepared By:".Auth::user()->name.". (Vision Cosmetics)";



        // $data= array(
        // 'username'=>"shajibazher",
        // 'password'=>"UtUs6B8WVqjmm72",
        // 'number'=>"$number",
        // 'message'=>"$text"
        // );

        //  $data2= array(
        // 'username'=>"shajibazher",
        // 'password'=>"UtUs6B8WVqjmm72",
        // 'number'=>"$return->user->phone",
        // 'message'=>"$text2"
        // );

        // $ch = curl_init(); // Initialize cURL

        // //Sms For Admin
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus = $p[0];


        //  //Sms For Customer
        // curl_setopt($ch, CURLOPT_URL,$url);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data2));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $smsresult = curl_exec($ch);
        // $p = explode("|",$smsresult);
        // $sendstatus2 = $p[0];





        // if($sendstatus == 1101){
        //     Toastr::success('Return Invoice Created Successfully');
        // }else{
        //     Toastr::success('Return Invoice Created Successfully', 'success');
        //     Toastr::error(VisionSmsResponse($sendstatus), 'error');
        // }

        // if($sendstatus2 == 1101){
        //     Toastr::success('An Sms has been send to '.$return->user->name .' Phone: '.$return->user->phone);
        // }else{
        //     Toastr::success('Return Invoice Created Successfully', 'success');
        //     Toastr::error(VisionSmsResponse($sendstatus), 'error');
        // }


        return $return->id;
        
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $returnDetails = Returnproduct::with('product','user')->findOrFail($id);
        if(empty($returnDetails->approved_by)){
            $signature = null;
        }else{
            $signature = Admin::where('id',$returnDetails->approved_by)->select('name','signature')->first();
        }
        return view('pos.return.show',compact('returnDetails','signature'));
    }


    public function edit($id)
    {

        $return =  Returnproduct::with('product')->findOrFail($id);
        $users = User::where('user_type','pos')->get();
        $products = Product::get();
        return view('pos.return.edit',compact('products','users','return'));
    }


    public function update(Request $request,$id){
             
        $this->validate($request,[
            'return_date' => 'required|date',
            'user_id' => 'required|numeric',
            'discount' => 'required|numeric',
            'carrying_and_loading' => 'required|numeric',
            'product' => 'required',
        ]);


         //Amount calculation
         $products = json_decode($request->product);

         $amount = [];
         foreach($products as  $item){
             $amount[] = ($item->count)*($item->price);
         }
         $netamount = array_sum($amount);
         $amount_total = ($netamount+$request->carrying_and_loading)-($request->discount);



       
        $return = Returnproduct::findOrFail($id);
        $return->user_id = $request->user_id;
        $return->discount = $request->discount;
        $return->carrying_and_loading = $request->carrying_and_loading;
        $return->amount = $amount_total;
        $return->returned_at = $request->return_date." ".Carbon::now()->toTimeString();
        $return->returned_by = Auth::user()->name;
        $return->save();

        DB::table('product_returnproduct')->where('returnproduct_id', '=', $id)->delete();

        
        $products = json_decode($request->product);
        $product_info = [];
        foreach($products as $product){
         $product_info[] = ['returnproduct_id' =>$return->id, 'product_id' => $product->id,'qty' => $product->count,'price' => $product->price, 'user_id' => $request->user_id,'returned_at' => $request->return_date." ".Carbon::now()->toTimeString()];   
        }

        $return->product()->attach($product_info);

        Toastr::success('Returned Successfully', 'success');
        return $return->id;
    }


    public function destroy($id)
    {
        $returnDetails = Returnproduct::findOrFail($id);
        if(Auth::user()->role->id == 2){
            Toastr::error('You Are Not Authorized', 'error');
            return redirect()->back();
        }else{
        $returnDetails->return_status = 2;
        $returnDetails->approved_by = Auth::user()->id;
        $returnDetails->deleted_at = now();
        $returnDetails->amount = 0;
        $returnDetails->save();
        $returnDetails->product()->detach();
        Toastr::success('Return cancelled Successfully', 'success');
        return redirect()->route('returnproduct.index');
        }
    }

    public function invoice(Request $request,$id){
        $general_opt = GeneralOption::first();
        $general_opt_value = json_decode($general_opt->options, true);
        $returnDetails = Returnproduct::with('product','user')->findOrFail($id);
        $current_user = User::findOrFail($returnDetails->user_id);
        if(empty($returnDetails->approved_by)){
           $signature = '';
        }else{
            $signature = Admin::where('id',$returnDetails->approved_by)->select('name','signature')->first();
        }

        $pdf = PDF::loadView('pos.return.invoice',compact('returnDetails','current_user','general_opt_value','signature'));
        return $pdf->download('invoice.pdf');
    }



    public function approve(Request $request,$id){
        $returnproduct = Returnproduct::findOrFail($id);
        $returnproduct->return_status = 1;
        $returnproduct->approved_by = Auth::user()->id;
        $returnproduct->save();
       return ['id'=> $returnproduct->id,'status' => $returnproduct->return_status,'msg' => 'Return Invoice Approved Successfully' ];
    }
}
