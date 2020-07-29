<?php

namespace App\Http\Controllers\Ecom;
use App\Deal;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Intervention\Image\Facades\Image;

class DealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deal = Deal::where('status','1')->first();
        return view('ecom.deal.index',compact('deal'));
    }



    public function create()
    {
        $checkdealexist = Deal::where('status',1)->get();
        if(count($checkdealexist) > 0){
            Toastr::error('Already A Deal Running', 'error');
            return redirect()->back();
        }
        $products = Product::where('type','ecom')->get();
        return view('ecom.deal.create',compact('products'));
    }



    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|max:40',
            'description' => 'required',
            'amount' => 'required|numeric',
            'product' => 'required|numeric',
            'image' => 'required|image',
            'btn_text' => 'required',
            'btn_url' => 'required',
            'expired_at' => 'required',
        ]);
        
        $deal = new Deal;


        if($request->hasFile('image')){
            //get form image
            $image = $request->file('image');
            $slug = Str::slug($request->title);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();
            //location for new image 
            $original_location = public_path('uploads/deals/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->save($original_location);
            $deal->image =  $image_name;
         }
        $deal->title = $request->title; 
        $deal->description = $request->description; 
        $deal->amount = $request->amount; 
        $deal->product = $request->product; 
        $deal->expired_at = $request->expired_at; 
        $deal->button_text = $request->btn_text; 
        $deal->button_url = $request->btn_url; 
        $deal->bg_color = $request->bg_color; 
        $deal->button_color = $request->button_color; 
        $deal->status = 1; 
        $deal->save();

        Toastr::success('Deal Added  Successfully', 'success');
        return redirect()->route('deals.index');
    }


    public function edit($id)
    {
        
        $deal = Deal::findOrFail($id);
        $products = Product::where('type','ecom')->get();
        return view('ecom.deal.edit',compact('products','deal'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title' => 'required|max:40',
            'description' => 'required',
            'amount' => 'required|numeric',
            'product' => 'required|numeric',
            'image' => 'image',
            'btn_text' => 'required',
            'btn_url' => 'required',
            'expired_at' => 'required',
        ]);
        
        $deal = Deal::findOrFail($id);


        if($request->hasFile('image')){
            //get form image
            $image = $request->file('image');
            $slug = Str::slug($request->title);
            $current_date = Carbon::now()->toDateString();
            //get unique name for image
            $image_name = $slug."-".$current_date.".".$image->getClientOriginalExtension();
            //location for new image 
            $original_location = public_path('uploads/deals/'.$image_name);
            //resize image for category and upload temp 
            Image::make($image)->save($original_location);
            $deal->image =  $image_name;
         }
        $deal->title = $request->title; 
        $deal->description = $request->description; 
        $deal->amount = $request->amount; 
        $deal->product = $request->product; 
        $deal->expired_at = $request->expired_at; 
        $deal->button_text = $request->btn_text; 
        $deal->button_url = $request->btn_url; 
        $deal->bg_color = $request->bg_color; 
        $deal->button_color = $request->button_color; 
        $deal->save();
        Toastr::success('Deal Updated Successfully', 'success');
        return redirect()->route('deals.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Deal  $deal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deal = Deal::findOrFail($id);
        $deal->status = 0;
        $deal->save();
        Toastr::warning('Deal Disabled Successfully', 'success');
        return redirect()->route('deals.index');
    }


}
