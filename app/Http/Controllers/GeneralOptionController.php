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
        $g_opt_value = json_decode($g_opt->options, true);
        return view('g_opt.index',compact('g_opt','g_opt_value'));
    }

    public function edit($id)
    {
        return false;
    }

    public function update(Request $request,$id){
        $this->validate($request,[
            'pageloader' => 'boolean',
            'slider' => 'boolean',
            'product_types' => 'boolean',
            'pd_type_noi' => 'required|digits_between:1,2',
            'new_pd' => 'boolean',
            'new_pd_noi' => 'required|digits_between:1,2',
            'hot_pd' => 'boolean',
            'hot_pd_noi' => 'required|digits_between:1,2',
            'pd_collection' => 'boolean',
            'pd_collection_noi' => 'required|digits_between:1,2',
        ]);
        if($request->has('pageloader')){ 
            $pageloader = 1;
        }else{ 
            $pageloader = 0;
        }

        if($request->has('slider')){ 
            $slider = 1;
        }else{ 
            $slider = 0;
        }
        if($request->has('product_types')){ 
            $product_types = 1;
        }else{ 
            $product_types = 0;
        }
        if($request->has('product_types_counter')){ 
            $product_types_counter = 1;
        }else{ 
            $product_types_counter = 0;
        }

        

        if($request->has('brands')){ 
            $brands = 1;
        }else{ 
            $brands = 0;
        }

        if($request->has('brands_counter')){ 
            $brands_counter = 1;
        }else{ 
            $brands_counter = 0;
        }

        if($request->has('new_pd')){ 
            $new_pd = 1;
        }else{ 
            $new_pd = 0;
        }
        if($request->has('hot_pd')){ 
            $hot_pd = 1;
        }else{ 
            $hot_pd = 0;
        }
        if($request->has('pd_collection')){ 
            $pd_collection = 1;
        }else{ 
            $pd_collection = 0;
        }
        
        if($request->has('collection_counter')){ 
            $collection_counter = 1;
        }else{ 
            $collection_counter = 0;
        }


        if($request->has('before_footer_infobox')){ 
            $before_footer_infobox = 1;
        }else{ 
            $before_footer_infobox = 0;
        }
        
        


        

        $option_arr = ['pageloader' => $pageloader,'slider'=> $slider,'product_types'=> $product_types,'product_types_counter' => $product_types_counter,'pd_type_noi' => $request->pd_type_noi,'brands' => $brands,'brands_counter'=> $brands_counter,'pd_brands_noi' => $request->pd_brands_noi,'new_pd' => $new_pd,'new_pd_noi' => $request->new_pd_noi,'hot_pd' => $hot_pd,'hot_pd_noi' => $request->hot_pd_noi,'pd_collection' => $pd_collection,'collection_counter' => $collection_counter,'pd_collection_noi' => $request->pd_collection_noi,'before_footer_infobox' => $before_footer_infobox];

        $g_opt = GeneralOption::findOrFail($id);
        $g_opt->options = $option_arr;
        $g_opt->save();
        Toastr::success('General Option  Updated Successfully', 'success');
        return redirect()->route('generaloption.index');
    }
}
