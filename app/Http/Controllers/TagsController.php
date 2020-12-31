<?php

namespace App\Http\Controllers;
use Session;
use App\Tags;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\TagStoreRequest;

class TagsController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:Product Section');
        $this->middleware('permission:Product Edit')->only('edit','update');
    }

    
    public function index()
    {
        $tags = Tags::orderBy('id','DESC')->paginate(10);
        return view('tags.index',compact('tags'));
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
    public function store(TagStoreRequest $request)
    {
        $tag = new Tags;
        $tag->tag_name = $request['tag_name'];
        $tag->save();
        return $tag->tag_name.' Stored Successfully';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tags  $tags
     * @return \Illuminate\Http\Response
     */
    public function show(Tags $tags)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tags  $tags
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tags = Tags::find($id);
        return $tags;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tags  $tags
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'tag_name' => 'required|unique:tags,tag_name,'.$id,
        ]);
        $tag = Tags::find($id);
        $tag->tag_name = $request->tag_name;
        $tag->save();
        return $tag->tag_name ." Updated Successfully";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tags  $tags
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tags = Tags::findOrFail($id);
        $tags->delete();
        Session::flash('delete_success', 'Your Tags Has been Deleted Successfully'); 
        return redirect()->back();
    }
}
