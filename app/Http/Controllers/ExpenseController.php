<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ExpenseCollection;

class ExpenseController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }

    
    public function index()
    {
        $expenses = Expense::all();
        return view('expense.index',compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'expense_date' => 'required|date',
            'amount' => 'required|integer',
            'reason' => 'required|max:30',
        ]);
        $expense = new Expense;
        $expense->expense_date = $request->expense_date;
        $expense->amount = $request->amount;
        $expense->reasons = $request->reason;
        $expense->admin_id = Auth::user()->id;
        $expense->save();
        return "Expense Stored Successfully";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Expense::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'expense_date' => 'required|date',
            'amount' => 'required|integer',
            'reason' => 'required|max:30',
        ]);
        $expense = Expense::findOrFail($id);
        $expense->expense_date = $request->expense_date;
        $expense->amount = $request->amount;
        $expense->reasons = $request->reason;
        $expense->admin_id = Auth::user()->id;
        $expense->save();
        return "Expense Updated Successfully";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }

    public function last10(){
        return  new ExpenseCollection(Expense::take(10)->orderBy('id','DESC')->get());
    }

    public function datewise(Request $request){
        $this->validate($request,[
            'start' => 'required|date',
            'end' => 'required|date',
        ]);
        $expenses = Expense::whereBetween('expense_date', [$request->start." 00:00:00", $request->end." 23:59:59"])->orderBy('expense_date', 'asc')->get();
        return view('expense.datewise',compact('expenses','request'));
    }

    public function datewiseGetMethod($start,$end){
        // $request = ['start' => $start, 'end' => $end];
        $expenses = new ExpenseCollection(Expense::whereBetween('expense_date', [$start." 00:00:00", $end." 23:59:59"])->orderBy('expense_date', 'asc')->get());
        return $expenses;
    }
}
