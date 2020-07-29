<?php

namespace App\Http\Controllers\Pos;


use App\Http\Controllers\Controller;
use App\Cash;
use App\User;
use Carbon\Carbon;
use App\Paymentmethod;
use Illuminate\Http\Request;
use App\Http\Requests\CashRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class CashController extends Controller
{

    public function index()
    {
        $users = User::where('user_type','pos')->get();
        $payment_methods = Paymentmethod::all();
        return view('pos.cash.index',compact('users','payment_methods'));
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


    public function show(Cash $cash)
    {
        //
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
        $cash->save();
        Toastr::success('Cash Updated Successfully', 'success');
        return redirect(route('cash.index'));

    }


    public function destroy(Cash $cash)
    {
        
    }
}
