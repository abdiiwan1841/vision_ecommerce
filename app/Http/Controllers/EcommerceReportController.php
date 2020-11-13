<?php

namespace App\Http\Controllers;

use PDF;
use App\User;
use App\Order;
use Carbon\Carbon;
use App\Returnproduct;
use Illuminate\Http\Request;

class EcommerceReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:Ecom Due Report')->only('EcomDueReport', 'EcomDueReportResult','pdfEcomDueReportResult');
        $this->middleware('permission:Ecom Statement')->only('ecomUserStatement','showEcomUserstatement','pdfEcomUserstatement');
        $this->middleware('permission:Ecommerce OrderReport')->only('PdfOrderReport','ShowOrderReport', 'OrderReport');

    }
    public function date_sort($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
    }
    public function date_sort_desc($a, $b) {
        return  strtotime($b['date']) - strtotime($a['date']);
    }

    public function ecomUserStatement(){
        $users = User::where('user_type','ecom')->get();
        return view('ecom.report.userstatement',compact('users'));
    }




    public function showEcomUserstatement(Request $request){
        $this->validate($request,[
            'user' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $current_user = User::findOrFail($request->user);
        $users = User::where('user_type','ecom')->get();


        $sales = Order::where('user_id',$request->user)->where('order_status',1)->whereBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('ordered_at', 'ASC')->get();
       

        $cashes = Order::where('user_id',$request->user)->where('payment_status',1)->whereBetween('paymented_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('paymented_at', 'ASC')->get();

        $returns = Returnproduct::where('user_id',$request->user)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('returned_at', 'ASC')->get();
     

        $salesinfo = [];
        foreach($sales as $sale){
            $salesinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$sale->ordered_at)->format('d-m-Y'), 'id' => $sale->id, 'particular'=>  'sales', 'debit' => $sale->amount,  'credit' => 0,'reference' => NULL];
        }

        $returninfo = [];
        foreach($returns as $return){
            $returninfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$return->returned_at)->format('d-m-Y'), 'id' => $return->id, 'particular'=>  'return', 'debit' => 0,  'credit' => $return->amount,'reference' => NULL];
        }



        $cashinfo = [];
        foreach($cashes as $cash){
            $cashinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$cash->paymented_at)->format('d-m-Y'), 'id' => $cash->id, 'particular'=>  'cash', 'debit' => 0,  'credit' => $cash->amount,'reference' => $cash->references];
        }


        $merge_data =  array_merge($salesinfo,$returninfo, $cashinfo);
        
        $datewise_sorted_data = [];
        foreach($merge_data as $merge){
            $datewise_sorted_data[] = ['date' => $merge['date'],'id' => $merge['id'],'particular' => $merge['particular'], 'debit' => $merge['debit'], 'credit' => $merge['credit'],'reference' => $merge['reference'] ];
        }
        usort($datewise_sorted_data,  array($this, "date_sort"));


        $previous_sales = Order::where('user_id',$request->user)->where('order_status',1)->whereNotBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_returns = Returnproduct::where('user_id',$request->user)->whereNotBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_cashes = Order::where('user_id',$request->user)->where('payment_status',1)->whereNotBetween('paymented_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('cash');


        $balance = ($previous_sales) - ($previous_returns + $previous_cashes);


        return view('ecom.report.showuserstatement',compact('datewise_sorted_data','request','users','balance','current_user'));

    }


    public function pdfEcomUserstatement(Request $request){
        $this->validate($request,[
            'user' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $current_user = User::findOrFail($request->user);
        $users = User::where('user_type','ecom')->get();


        $sales = Order::where('user_id',$request->user)->where('order_status',1)->whereBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('ordered_at', 'ASC')->get();
       

        $cashes = Order::where('user_id',$request->user)->where('payment_status',1)->whereBetween('paymented_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('paymented_at', 'ASC')->get();

        $returns = Returnproduct::where('user_id',$request->user)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('returned_at', 'ASC')->get();
     

        $salesinfo = [];
        foreach($sales as $sale){
            $salesinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$sale->ordered_at)->format('d-m-Y'), 'id' => $sale->id, 'particular'=>  'sales', 'debit' => $sale->amount,  'credit' => 0,'reference' => NULL];
        }

        $returninfo = [];
        foreach($returns as $return){
            $returninfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$return->returned_at)->format('d-m-Y'), 'id' => $return->id, 'particular'=>  'return', 'debit' => 0,  'credit' => $return->amount,'reference' => NULL];
        }



        $cashinfo = [];
        foreach($cashes as $cash){
            $cashinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$cash->paymented_at)->format('d-m-Y'), 'id' => $cash->id, 'particular'=>  'cash', 'debit' => 0,  'credit' => $cash->amount,'reference' => $cash->references];
        }


        $merge_data =  array_merge($salesinfo,$returninfo, $cashinfo);
        
        $datewise_sorted_data = [];
        foreach($merge_data as $merge){
            $datewise_sorted_data[] = ['date' => $merge['date'],'id' => $merge['id'],'particular' => $merge['particular'], 'debit' => $merge['debit'], 'credit' => $merge['credit'],'reference' => $merge['reference'] ];
        }
        usort($datewise_sorted_data,  array($this, "date_sort"));


        $previous_sales = Order::where('user_id',$request->user)->where('order_status',1)->whereNotBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_returns = Returnproduct::where('user_id',$request->user)->whereNotBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_cashes = Order::where('user_id',$request->user)->where('payment_status',1)->whereNotBetween('paymented_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('cash');


        $balance = ($previous_sales) - ($previous_returns + $previous_cashes);


        
        $pdf = PDF::loadView('ecom.report.pdfuserstatement',compact('datewise_sorted_data','request','users','balance','current_user'));
        return $pdf->download('invoice.pdf');

    }





    public function EcomDueReport(){
        return view('ecom.report.dueresult');
    }

    
    public function EcomDueReportResult(Request $request){
        $this->validate($request,[
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

    
    $ecomcustomer = User::where('user_type','ecom')->get();

    $division_report = [];

    foreach($ecomcustomer as $customer){



        $sales = Order::where('user_id',$customer->id)->where('order_status',1)->whereBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $returns = Returnproduct::where('user_id',$customer->id)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $cashes = Order::where('user_id',$customer->id)->where('payment_status',1)->whereBetween('paymented_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('cash');


        $previous_sales = Order::where('user_id',$customer->id)->where('order_status',1)->whereNotBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_returns = Returnproduct::where('user_id',$customer->id)->whereNotBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_cashes = Order::where('user_id',$customer->id)->where('payment_status',1)->whereNotBetween('paymented_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('cash');

        $prev_balance = ($previous_sales)-( $previous_cashes+$previous_returns);

        $division_report [] = ['customer' => $customer->name,'address' => $customer->address,'sales' => $sales, 'returns' => $returns, 'cashes' => $cashes ,'prev_balance' =>  $prev_balance];


    }

    return view('ecom.report.showdueresult',compact('division_report','request'));
        

    }

    public function pdfEcomDueReportResult(Request $request){
        $this->validate($request,[
            'start' => 'required|date',
            'end' => 'required|date',
        ]);


    
    $ecomcustomer = User::where('user_type','ecom')->get();


    $division_report = [];

    foreach($ecomcustomer as $customer){



        $sales = Order::where('user_id',$customer->id)->where('order_status',1)->whereBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $returns = Returnproduct::where('user_id',$customer->id)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $cashes = Order::where('user_id',$customer->id)->where('payment_status',1)->whereBetween('paymented_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('cash');


        $previous_sales = Order::where('user_id',$customer->id)->where('order_status',1)->whereNotBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_returns = Returnproduct::where('user_id',$customer->id)->whereNotBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_cashes = Order::where('user_id',$customer->id)->where('payment_status',1)->whereNotBetween('paymented_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('cash');


        $prev_balance = ($previous_sales)-( $previous_cashes+$previous_returns);



        $division_report [] = ['customer' => $customer->name,'address' => $customer->address,'sales' => $sales, 'returns' => $returns, 'cashes' => $cashes ,'prev_balance' =>  $prev_balance];


    }

    $pdf = PDF::loadView('ecom.report.pdfshowdueresult',compact('division_report','request'));
    return $pdf->download('Ecommerce_Report'.now().'.pdf');
        

    }


    public function OrderReport(){
         return view('ecom.report.orderreport');
    }


    public function ShowOrderReport(Request $request){
        $this->validate($request,[
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

         $orders = Order::with('product')->where('order_status',1)->whereBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->get();

         return view('ecom.report.showorderreport',compact('orders','request'));
    }


    public function PdfOrderReport(Request $request){
        $this->validate($request,[
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

       
        $orders = Order::with('product')->where('order_status',1)->whereBetween('ordered_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->get();

        $pdf = PDF::loadView('ecom.report.pdforderreport',compact('orders','request'));
        return $pdf->download('Ecommerce Order Report'.now().'.pdf');


    }






}
