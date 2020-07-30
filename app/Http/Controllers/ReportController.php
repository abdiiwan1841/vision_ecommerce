<?php

namespace App\Http\Controllers;

use PDF;
use App\Cash;
use App\Sale;
use App\User;
use App\Order;
use App\Prevdue;
use App\Division;
use Carbon\Carbon;
use App\Returnproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{


    public function date_sort($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
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






    public function posUserStatement(){
        $users = User::where('user_type','pos')->get();
        return view('pos.report.userstatement',compact('users'));
    }

   

    public function showPosUserstatement(Request $request){
        $this->validate($request,[
            'user' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $current_user = User::findOrFail($request->user);
        $users = User::where('user_type','pos')->get();


        $prevdues = Prevdue::where('user_id',$request->user)->whereBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('due_at', 'ASC')->get();

        $sales = Sale::where('user_id',$request->user)->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('sales_at', 'ASC')->get();


        $returns = Returnproduct::where('user_id',$request->user)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('returned_at', 'ASC')->get();
       

        $cashes = Cash::where('user_id',$request->user)->whereBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('received_at', 'ASC')->get();
     

        $salesinfo = [];
        foreach($sales as $sale){
            $salesinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$sale->sales_at)->format('d-m-Y'), 'id' => $sale->id, 'particular'=>  'sales', 'debit' => $sale->amount,  'credit' => 0,'reference' => NULL];
        }

        $returninfo = [];
        foreach($returns as $return){
            $returninfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$return->returned_at)->format('d-m-Y'), 'id' => $return->id, 'particular'=>  'return', 'debit' => 0,  'credit' => $return->amount,'reference' => NULL];
        }

        $prevdue_info = [];

        foreach($prevdues as $pvd){
            $prevdue_info[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$pvd->due_at)->format('d-m-Y'), 'id' => $pvd->id, 'particular'=>  'prevdue', 'debit' => $pvd->amount,  'credit' => 0,'reference' => $pvd->reference];
        }

        $cashinfo = [];
        foreach($cashes as $cash){
            $cashinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$cash->received_at)->format('d-m-Y'), 'id' => $cash->id, 'particular'=>  'cash', 'debit' => 0,  'credit' => $cash->amount,'reference' => $cash->reference];
        }


        $merge_data =  array_merge($salesinfo,$returninfo, $cashinfo,$prevdue_info);
        
        $datewise_sorted_data = [];
        foreach($merge_data as $merge){
            $datewise_sorted_data[] = ['date' => $merge['date'],'id' => $merge['id'],'particular' => $merge['particular'], 'debit' => $merge['debit'], 'credit' => $merge['credit'],'reference' => $merge['reference'] ];
        }
        usort($datewise_sorted_data,  array($this, "date_sort"));


        $previous_sales = Sale::where('user_id',$request->user)->whereNotBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_returns = Returnproduct::where('user_id',$request->user)->whereNotBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_cashes = Cash::where('user_id',$request->user)->whereNotBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_prevdue = Prevdue::where('user_id',$request->user)->whereNotBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $balance = ($previous_sales+$previous_prevdue) - ($previous_returns + $previous_cashes);


        return view('pos.report.showuserstatement',compact('datewise_sorted_data','request','users','balance','current_user'));

    }







    public function pdfPosUserstatement(Request $request){
        $this->validate($request,[
            'user' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $current_user = User::findOrFail($request->user);
        $users = User::where('user_type','pos')->get();


        $prevdues = Prevdue::where('user_id',$request->user)->whereBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('due_at', 'ASC')->get();

        $sales = Sale::where('user_id',$request->user)->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('sales_at', 'ASC')->get();


        $returns = Returnproduct::where('user_id',$request->user)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('returned_at', 'ASC')->get();
       

        $cashes = Cash::where('user_id',$request->user)->whereBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('received_at', 'ASC')->get();
     

        $salesinfo = [];
        foreach($sales as $sale){
            $salesinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$sale->sales_at)->format('d-m-Y'), 'id' => $sale->id, 'particular'=>  'sales', 'debit' => $sale->amount,  'credit' => 0,'reference' => NULL];
        }

        $returninfo = [];
        foreach($returns as $return){
            $returninfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$return->returned_at)->format('d-m-Y'), 'id' => $return->id, 'particular'=>  'return', 'debit' => 0,  'credit' => $return->amount,'reference' => NULL];
        }

        $prevdue_info = [];

        foreach($prevdues as $pvd){
            $prevdue_info[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$pvd->due_at)->format('d-m-Y'), 'id' => $pvd->id, 'particular'=>  'prevdue', 'debit' => $pvd->amount,  'credit' => 0,'reference' => $pvd->reference];
        }

        $cashinfo = [];
        foreach($cashes as $cash){
            $cashinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$cash->received_at)->format('d-m-Y'), 'id' => $cash->id, 'particular'=>  'cash', 'debit' => 0,  'credit' => $cash->amount,'reference' => $cash->reference];
        }


        $merge_data =  array_merge($salesinfo,$returninfo, $cashinfo,$prevdue_info);
        
        $datewise_sorted_data = [];
        foreach($merge_data as $merge){
            $datewise_sorted_data[] = ['date' => $merge['date'],'id' => $merge['id'],'particular' => $merge['particular'], 'debit' => $merge['debit'], 'credit' => $merge['credit'],'reference' => $merge['reference'] ];
        }
        usort($datewise_sorted_data,  array($this, "date_sort"));


        $previous_sales = Sale::where('user_id',$request->user)->whereNotBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_returns = Returnproduct::where('user_id',$request->user)->whereNotBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_cashes = Cash::where('user_id',$request->user)->whereNotBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_prevdue = Prevdue::where('user_id',$request->user)->whereNotBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $balance = ($previous_sales+$previous_prevdue) - ($previous_returns + $previous_cashes);

        $pdf = PDF::loadView('pos.report.pdfuserstatement',compact('datewise_sorted_data','request','users','balance','current_user'));
        return $pdf->download('invoice.pdf');


    }




    public function posDeatilStatement(){
        $users = User::where('user_type','pos')->get();
        return view('pos.report.detailstatement',compact('users'));
    }


    public function showPosDeatilStatement(Request $request){
        $this->validate($request,[
            'user' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $current_user = User::findOrFail($request->user);
        $users = User::where('user_type','pos')->get();


       
        $prevdues = Prevdue::where('user_id',$request->user)->whereBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('due_at', 'ASC')->get();

        $sales = Sale::with('product')->where('user_id',$request->user)->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('sales_at', 'ASC')->get();


        $returns = Returnproduct::with('product')->where('user_id',$request->user)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('returned_at', 'ASC')->get();
       

        $cashes = Cash::where('user_id',$request->user)->whereBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('received_at', 'ASC')->get();
     

        $salesinfo = [];
        foreach($sales as $sale){
            $salesinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$sale->sales_at)->format('d-m-Y'), 'id' => $sale->id, 'particular'=>  'sales', 'debit' => $sale->amount,  'credit' => 0,'product' => $sale->product,'discount'=> $sale->discount, 'carrying_and_loading' => $sale->carrying_and_loading,'reference' => ''];
        }

        $returninfo = [];
        foreach($returns as $return){
            $returninfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$return->returned_at)->format('d-m-Y'), 'id' => $return->id, 'particular'=>  'return', 'debit' => 0,  'credit' => $return->amount,'product' => $return->product,'discount'=> $return->discount, 'carrying_and_loading' => $return->carrying_and_loading,'reference' => ''];
        }

        $prevdue_info = [];

        foreach($prevdues as $pvd){
            $prevdue_info[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$pvd->due_at)->format('d-m-Y'), 'id' => $pvd->id, 'particular'=>  'prevdue', 'debit' => $pvd->amount,  'credit' => 0,'product' => [],'discount'=> 0, 'carrying_and_loading' => 0,'reference' => $pvd->reference];
        }

        $cashinfo = [];
        foreach($cashes as $cash){
            $cashinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$cash->received_at)->format('d-m-Y'), 'id' => $cash->id, 'particular'=>  'cash', 'debit' => 0,  'credit' => $cash->amount,'product' => [],'discount'=> 0, 'carrying_and_loading' => 0,'reference' => $cash->reference];
        }


        $merge_data =  array_merge($salesinfo,$returninfo,$cashinfo,$prevdue_info);
        
        $datewise_sorted_data = [];
        foreach($merge_data as $merge){
            $datewise_sorted_data[] = ['date' => $merge['date'],'id' => $merge['id'],'particular' => $merge['particular'], 'debit' => $merge['debit'], 'credit' => $merge['credit'],'product' => $merge['product'], 'discount' => $merge['discount'], 'carrying_and_loading' => $merge['carrying_and_loading'],'reference' => $merge['reference']];
        }
        usort($datewise_sorted_data,  array($this, "date_sort"));


        $previous_sales = Sale::where('user_id',$request->user)->whereNotBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_returns = Returnproduct::where('user_id',$request->user)->whereNotBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_cashes = Cash::where('user_id',$request->user)->whereNotBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_prevdue = Prevdue::where('user_id',$request->user)->whereNotBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $balance = ($previous_sales+$previous_prevdue) - ($previous_returns + $previous_cashes);


        return view('pos.report.showdetailstatements',compact('datewise_sorted_data','request','users','balance','current_user'));

    }




    
    public function pdfPosDeatilStatement(Request $request){
        $this->validate($request,[
            'user' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $current_user = User::findOrFail($request->user);
        $users = User::where('user_type','pos')->get();


       
        $prevdues = Prevdue::where('user_id',$request->user)->whereBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('due_at', 'ASC')->get();

        $sales = Sale::with('product')->where('user_id',$request->user)->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('sales_at', 'ASC')->get();


        $returns = Returnproduct::with('product')->where('user_id',$request->user)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('returned_at', 'ASC')->get();
       

        $cashes = Cash::where('user_id',$request->user)->whereBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('received_at', 'ASC')->get();
     

        $salesinfo = [];
        foreach($sales as $sale){
            $salesinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$sale->sales_at)->format('d-m-Y'), 'id' => $sale->id, 'particular'=>  'sales', 'debit' => $sale->amount,  'credit' => 0,'product' => $sale->product,'discount'=> $sale->discount, 'carrying_and_loading' => $sale->carrying_and_loading,'reference' => ''];
        }

        $returninfo = [];
        foreach($returns as $return){
            $returninfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$return->returned_at)->format('d-m-Y'), 'id' => $return->id, 'particular'=>  'return', 'debit' => 0,  'credit' => $return->amount,'product' => $return->product,'discount'=> $return->discount, 'carrying_and_loading' => $return->carrying_and_loading,'reference' => ''];
        }

        $prevdue_info = [];

        foreach($prevdues as $pvd){
            $prevdue_info[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$pvd->due_at)->format('d-m-Y'), 'id' => $pvd->id, 'particular'=>  'prevdue', 'debit' => $pvd->amount,  'credit' => 0,'product' => [],'discount'=> 0, 'carrying_and_loading' => 0,'reference' => $pvd->reference];
        }

        $cashinfo = [];
        foreach($cashes as $cash){
            $cashinfo[] = ['date' => Carbon::createFromFormat('Y-m-d H:i:s',$cash->received_at)->format('d-m-Y'), 'id' => $cash->id, 'particular'=>  'cash', 'debit' => 0,  'credit' => $cash->amount,'product' => [],'discount'=> 0, 'carrying_and_loading' => 0,'reference' => $cash->reference];
        }


        $merge_data =  array_merge($salesinfo,$returninfo,$cashinfo,$prevdue_info);
        
        $datewise_sorted_data = [];
        foreach($merge_data as $merge){
            $datewise_sorted_data[] = ['date' => $merge['date'],'id' => $merge['id'],'particular' => $merge['particular'], 'debit' => $merge['debit'], 'credit' => $merge['credit'],'product' => $merge['product'], 'discount' => $merge['discount'], 'carrying_and_loading' => $merge['carrying_and_loading'],'reference' => $merge['reference']];
        }
        usort($datewise_sorted_data,  array($this, "date_sort"));


        $previous_sales = Sale::where('user_id',$request->user)->whereNotBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_returns = Returnproduct::where('user_id',$request->user)->whereNotBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_cashes = Cash::where('user_id',$request->user)->whereNotBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_prevdue = Prevdue::where('user_id',$request->user)->whereNotBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $balance = ($previous_sales+$previous_prevdue) - ($previous_returns + $previous_cashes);



        $pdf = PDF::loadView('pos.report.pdfdetailstatements',compact('datewise_sorted_data','request','users','balance','current_user'));
        return $pdf->download('invoice.pdf');


    }


    public function DivisionReport(){
        $divisions =  Division::all();
        return view('pos.report.divisionresult',compact('divisions'));
    }

    public function DivisionReportResult(Request $request){
        $this->validate($request,[
            'division' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

    $divisions =  Division::all();
    $d_info = Division::findOrFail($request->division);
    
    $poscustomer = User::where('user_type','pos')->where('division_id',$request->division)->get();


    $division_report = [];

    foreach($poscustomer as $customer){

        
        $prevdues = Prevdue::where('user_id',$customer->id)->whereBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $sales = Sale::where('user_id',$customer->id)->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');


        $returns = Returnproduct::with('product')->where('user_id',$customer->id)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');
       

        $cashes = Cash::where('user_id',$customer->id)->whereBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');



        $previous_prevdue =Prevdue::where('user_id',$customer->id)->whereNotBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_sales = Sale::where('user_id',$customer->id)->whereNotBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');


        $previous_returns = Returnproduct::with('product')->where('user_id',$customer->id)->whereNotBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');
       

        $previous_cashes = Cash::where('user_id',$customer->id)->whereNotBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $prev_balance = ($previous_sales+ $previous_prevdue)-( $previous_cashes+$previous_returns);



        $division_report [] = ['customer' => $customer->name,'address' => $customer->address,'sales' => $sales, 'returns' => $returns, 'cashes' => $cashes,'prevdues' => $prevdues, 'prev_balance' =>  $prev_balance];


    }

    return view('pos.report.showdivisionresult',compact('division_report','request','divisions','d_info'));
        

    }


    public function pdfDivisionReportResult(Request $request){
        $this->validate($request,[
            'division' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

    $divisions =  Division::all();
    $d_info = Division::findOrFail($request->division);
    
    $poscustomer = User::where('user_type','pos')->where('division_id',$request->division)->get();


    $division_report = [];

    foreach($poscustomer as $customer){

        
        $prevdues = Prevdue::where('user_id',$customer->id)->whereBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $sales = Sale::where('user_id',$customer->id)->whereBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');


        $returns = Returnproduct::with('product')->where('user_id',$customer->id)->whereBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');
       

        $cashes = Cash::where('user_id',$customer->id)->whereBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');



        $previous_prevdue =Prevdue::where('user_id',$customer->id)->whereNotBetween('due_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $previous_sales = Sale::where('user_id',$customer->id)->whereNotBetween('sales_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');


        $previous_returns = Returnproduct::with('product')->where('user_id',$customer->id)->whereNotBetween('returned_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');
       

        $previous_cashes = Cash::where('user_id',$customer->id)->whereNotBetween('received_at', [$request->start." 00:00:00", $request->end." 23:59:59"])->sum('amount');

        $prev_balance = ($previous_sales+ $previous_prevdue)-( $previous_cashes+$previous_returns);



        $division_report [] = ['customer' => $customer->name,'address' => $customer->address,'sales' => $sales, 'returns' => $returns, 'cashes' => $cashes,'prevdues' => $prevdues, 'prev_balance' =>  $prev_balance];


    }

    $pdf = PDF::loadView('pos.report.pdfshowdivisionresult',compact('division_report','request','divisions','d_info'));
    return $pdf->download('invoice.pdf');

    }


    public function EcomDivisionReport(){
        $divisions =  Division::all();
        return view('ecom.report.divisionresult',compact('divisions'));
    }

    
    public function EcomDivisionReportResult(Request $request){
        $this->validate($request,[
            'division' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

    $divisions =  Division::all();
    $d_info = Division::findOrFail($request->division);
    
    $ecomcustomer = User::where('user_type','ecom')->where('division_id',$request->division)->get();


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

    return view('ecom.report.showdivisionresult',compact('division_report','request','divisions','d_info'));
        

    }

    public function pdfEcomDivisionReportResult(Request $request){
        $this->validate($request,[
            'division' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

    $divisions =  Division::all();
    $d_info = Division::findOrFail($request->division);
    
    $ecomcustomer = User::where('user_type','ecom')->where('division_id',$request->division)->get();


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

    $pdf = PDF::loadView('ecom.report.pdfshowdivisionresult',compact('division_report','request','divisions','d_info'));
    return $pdf->download('invoice.pdf');
        

    }



}