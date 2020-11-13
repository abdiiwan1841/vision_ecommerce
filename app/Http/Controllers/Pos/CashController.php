<?php

namespace App\Http\Controllers\Pos;


use App\Cash;
use App\User;
use App\Admin;
use Carbon\Carbon;
use App\Paymentmethod;
use Illuminate\Http\Request;
use App\Http\Requests\CashRequest;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class CashController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:Inventory Cashes');
        $this->middleware('permission:Inventory Edit Cashes')->only('edit','update');
        $this->middleware('permission:Inventory Approval Cashes')->only('approve');
        $this->middleware('permission:Inventory Cancel Cashes')->only('cancel');
    }
    
    public function index()
    {
        $cashes = Cash::take(10)->orderBy('id', 'desc')->get();
        $users = User::where('user_type','pos')->get();
        $payment_methods = Paymentmethod::all();
        return view('pos.cash.index',compact('users','payment_methods','cashes'));
    }

    
    public function result(Request $request){
        $this->validate($request,[
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $users = User::where('user_type','pos')->get();
        $payment_methods = Paymentmethod::all();

        $cashes = Cash::with('paymentmethod')->whereBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('received_at', 'asc')->get();
        return view('pos.cash.cashshow',compact('cashes','users','payment_methods','request'));
    }


    public function create()
    {
        //
    }


    public function store(CashRequest $request)
    {

        
        $cash = new Cash;
        $cash->user_id = $request->user;
        $cash->amount = $request->amount;
        $cash->reference = $request->reference;
        $cash->paymentmethod_id = $request->payment_method;
        $cash->posted_by = Auth::user()->name;
        $cash->received_at = $request->received_at." ".Carbon::now()->toTimeString();
        $cash->save();
        Toastr::success('Cash Saved Successfully', 'success');
        return redirect(route('cash.index'));

    }


    public function show($id)
    {
        $admin = '';
        $cash = Cash::findOrFail($id);
        if(!empty($cash->approved_by)){
        $admin = Admin::findOrFail($cash->approved_by);
        }
        return view('pos.cash.show',compact('cash','admin'));
    }


    public function edit(Cash $cash)
    {
        return $cash;
    }

    public function update(CashRequest $request, Cash $cash)
    {
        
        $cash->amount = $request->amount;
        $cash->user_id = $request->user;
        $cash->reference = $request->reference;
        $cash->paymentmethod_id = $request->payment_method;
        $cash->posted_by = Auth::user()->name;
        $cash->received_at = $request->received_at." ".Carbon::now()->toTimeString();
        $cash->status = 0;
        $cash->approved_by =null;
        $cash->save();
        Toastr::success('Cash Updated Successfully', 'success');
        return redirect(route('cash.index'));

    }


    public function destroy(Cash $cash)
    {
        
    }

    public function approve(Request $request,$id){

        $cash = Cash::findOrFail($id);
        $cash->status = 1;
        $cash->approved_by = Auth::user()->id;
        $cash->save();
        return $id;
    }

    public function cancel(Request $request,$id){
        if(Auth::user()->role->id == 2){
            Toastr::error('You Are Not Authorized', 'error');
            return redirect()->back();
        }else{
        $cash = Cash::findOrFail($id);
        $cash->status = 2;
        $cash->amount = 0;
        $cash->approved_by = Auth::user()->id;
        $cash->save();
        Toastr::success('Cash Canceled Successfully', 'success');
        return redirect()->back();
        }
    }

    



}
