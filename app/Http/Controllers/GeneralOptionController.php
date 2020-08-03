<?php

namespace App\Http\Controllers;

use App\GeneralOption;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class GeneralOptionController extends Controller
{
    public function index()
    {
        $g_opt = GeneralOption::first();
        return view('g_opt.index',compact('g_opt'));
    }

    public function edit($id)
    {
         $g_opt = GeneralOption::findOrFail($id);
         $g_opt_value = json_decode($g_opt->options, true);
         return  view('g_opt.edit',compact('g_opt','g_opt_value'));
    }

    public function update(Request $request,$id){
        $this->validate($request,[
            'pageloader' => 'boolean',
        ]);
        if($request->has('pageloader')){ 
            $pageloader = 1;
        }else{ 
            $pageloader = 0;
        }
        $option_arr = ['pageloader' => $pageloader];

        $g_opt = GeneralOption::findOrFail($id);
        $g_opt->options = $option_arr;
        $g_opt->save();
        Toastr::success('General Option  Updated Successfully', 'success');
        return redirect()->route('generaloption.index');
    }
}
