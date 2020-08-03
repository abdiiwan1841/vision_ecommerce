<?php

namespace App\Http\Controllers\Frontend;

use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index($slug){
        $page = Page::where('slug',$slug)->first();
        return view('frontend.page.index',compact('page'));
    }
}
