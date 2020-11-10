<?php

namespace App\Http\Controllers;

use Session;
use App\Comments;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class CommentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:Ecommerce Section');
        
    }

    
    public function index(){
        $comments = Comments::all();
        return view('ecom.comments.index',compact('comments'));
    }

    public function store(Request $request, $id){
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required|Max:400',
        ]);
        $comment = new Comments;
        $comment->product_id = $request->id;
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comments = $request->message;
        $comment->status = 0;
        $comment->save();
        Session::flash('success','comment Posted needed  approval by admin');
        return redirect()->back();
    }

    public function approve($id){
        $comment = Comments::findOrFail($id);
        $comment->status = 1;
        $comment->save();
        Toastr::success('Comments Approve Successfully', 'success');
        return redirect()->back();
    }
    public function destroy($id){
        $comment = Comments::findOrFail($id);
        $comment->forceDelete();
        Toastr::success('Comments Deleted Successfully', 'success');
        return redirect()->back();
    }
}
