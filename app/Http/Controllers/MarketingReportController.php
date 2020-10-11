<?php

namespace App\Http\Controllers;

use App\Product;
use App\Employee;
use App\MarketingReport;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class MarketingReportController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }

    
    public function index(){
        $marketingreport = MarketingReport::take(10)->orderBy('id','DESC')->get();
        return view('marketingreport.index',compact('marketingreport'));
    }

    public function create(){
        $products = Product::where('type','!=','raw')->get();
        $employees = Employee::all();
        return view('marketingreport.create',compact('employees','products'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'employee' => 'required',
            'area' => 'max:20',
            'market' => 'max:20',
            'order' => 'integer',
            'delivery' => 'integer',
            'comment' => 'max:40',
        ]);
        
        $marketingreport = new MarketingReport;
        $marketingreport->at = $request->at;
        $marketingreport->employee_id = $request->employee;
        $marketingreport->area = $request->area;
        $marketingreport->order = $request->order;
        $marketingreport->delivery = $request->delivery;
        $marketingreport->market = $request->market;
        $marketingreport->comment = $request->comment;
        if(strlen($request->productinfo) < 6){
            $marketingreport->productinfo = null;
        }else{
            $marketingreport->productinfo = $request->productinfo;
        }
        $marketingreport->save();
        Toastr::success('Marketing Report Saved Successfully', 'success');
        return redirect()->route('marketingreport.index');

        
    }


    public function edit($id){
        $products = Product::where('type','!=','raw')->get();
        $employees = Employee::all();
        $marketingreport = MarketingReport::findOrFail($id);
        return view('marketingreport.edit',compact('marketingreport','products','employees'));
    }



    public function update(Request $request,$id){
        $this->validate($request,[
            'employee' => 'required',
            'area' => 'max:20',
            'market' => 'max:20',
            'order' => 'integer',
            'delivery' => 'integer',
            'comment' => 'max:40',
        ]);
        
        $marketingreport = MarketingReport::findOrFail($id);
        $marketingreport->at = $request->at;
        $marketingreport->employee_id = $request->employee;
        $marketingreport->area = $request->area;
        $marketingreport->order = $request->order;
        $marketingreport->delivery = $request->delivery;
        $marketingreport->market = $request->market;
        $marketingreport->comment = $request->comment;
        if(strlen($request->productinfo) < 6){
            $marketingreport->productinfo = null;
        }else{
            $marketingreport->productinfo = $request->productinfo;
        }
        $marketingreport->save();
        Toastr::success('Marketing Report Updated Successfully', 'success');
        return redirect()->route('marketingreport.index');

        
    }

    public function destroy($id){
        $marketingreport = MarketingReport::findOrFail($id);
        $marketingreport->delete();
        Toastr::success('Marketing Report Move To Trash', 'success');
        return redirect()->back();
    }
    public function datewiseview(Request $request){
        $this->validate($request,[
            'start' => 'required|date',
            'end' => 'required|date',
        ]);
        $marketingreport = MarketingReport::whereBetween('at', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('at', 'asc')->get();
        return view('marketingreport.datewise',compact('marketingreport','request'));
    }
}
