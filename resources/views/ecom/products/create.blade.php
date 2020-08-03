
@extends('layouts.adminlayout')
@section('title','Add New Product')

@section('modal')
<!--Category Modal -->
@component('component.common.modal')

    @slot('modal_id')
      categoryDataModal
    @endslot
    @slot('modal_button_class')
    add_modal_submit
    @endslot
    @slot('modal_title')
      Add Category
    @endslot

    @slot('modal_form') 
       <form action="{{route('categories.store')}}" method="POST" id="addForm" enctype="multipart/form-data">
        @csrf
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="category_name">Category Name</label>
      <input type="text" class="form-control @error('product_name') is-invalid @enderror" name="category_name" id="category_name" placeholder="Enter Category Name" value="{{old('category_name')}}">
        @error('category_name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>

      <div class="form-group">
        <label for="image">Category Image</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
        @error('image')
        <small class="form-error">{{ $message }}</small>
        @enderror

      </div>

      <div class="form-group">
        <img  style="padding: 10px;display:none" class="img-thumbnail rounded" src="" id="pd_image2" alt="">
      </div>




      
      
    @endslot
@endcomponent
<!--End Insert Modal -->





<!--Insert Modal -->
@component('component.common.modal')

    @slot('modal_id')
        DataModal
    @endslot
    @slot('modal_button_class')
    add_modal_submit
    @endslot
    @slot('modal_title')
      Add Sizes
    @endslot

    @slot('modal_form') 
       <form action="{{route('sizes.store')}}" method="POST" id="addForm">
        @csrf
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="name">Size</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Enter Size" value="{{old('name')}}" required>
        @error('name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>
      
    @endslot
@endcomponent



@component('component.common.modal')

    @slot('modal_id')
        SubcatDataModal
    @endslot
    @slot('modal_button_class')
    add_modal_submit
    @endslot
    @slot('modal_title')
      Add Product Types
    @endslot

    @slot('modal_form') 
       <form action="{{route('subcategories.store')}}" method="POST" id="addSubcat" enctype="multipart/form-data">
        @csrf
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="subcategory_name">Product Types</label>
      <input type="text" onclick="addSubcategory()" class="form-control @error('subcategory_name') is-invalid @enderror" name="subcategory_name" id="subcategory_name" placeholder="Enter Product Types" value="{{old('subcategory_name')}}" required>
        @error('name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>
      <div class="form-group">
        <label for="image">Product Type Image</label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" required>
        @error('image')
        <small class="form-error">{{ $message }}</small>
        @enderror

      </div>

      <div class="form-group">
        <img  style="padding: 10px;display:none" class="img-thumbnail rounded" src="" id="pd_image2" alt="">
      </div>
      
    @endslot
@endcomponent



@component('component.common.modal')

    @slot('modal_id')
        tagDataModal
    @endslot
    @slot('modal_button_class')
    add_modal_submit
    @endslot
    @slot('modal_title')
      Add Tag
    @endslot

    @slot('modal_form') 
       <form action="{{route('tags.store')}}" method="POST">
        @csrf
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="tag_name">Tag Name</label>
      <input type="text" class="form-control @error('tag_name') is-invalid @enderror" name="tag_name" id="tag_name" placeholder="Enter Tag Name" value="{{old('tag_name')}}" required>
        @error('tag_name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>
      
    @endslot
@endcomponent


@component('component.common.modal')

    @slot('modal_id')
        brandDataModal
    @endslot
    @slot('modal_button_class')
    add_modal_submit
    @endslot
    @slot('modal_title')
      Add Brand
    @endslot

    @slot('modal_form') 
       <form action="{{route('brands.store')}}" method="POST">
        @csrf
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="brand_name">Brand Name</label>
      <input type="text" class="form-control @error('brand_name') is-invalid @enderror" name="brand_name" id="brand_name" placeholder="Enter Brand Name" value="{{old('brand_name')}}" required>
        @error('brand_name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>
      
    @endslot
@endcomponent





@endsection






@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
	<div class="card">
    <div class="card-header">
      <div class="row">
        
        <div class="col-lg-4">
        <a class="btn btn-info" href="{{route('products.index')}}">back</a>
        </div>
        <div class="col-lg-8">
          <h5 class="card-title text-right">ADD NEW ECOMMERCE PRODUCTS</h5>
           <button type="button" onclick="reset()" id="reset" class="btn btn-success float-right"><i class="fa fa-sync-alt"></i>  <b>RESET THIS FORM</b></button>
        </div>
      </div>
         
    </div>
    <div class="card-body table-responsive">
    <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
     @csrf

     @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="product_name">Product Name</label>
          <input type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name" id="product_name" placeholder="Enter Product Name" oninput="saveValue(this)" value="{{old('product_name')}}" required>
            @error('product_name')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
    
          
  
          <div class="form-group">
            <label for="price">Product Price</label>
            <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" id="price" placeholder="Enter Price" value="{{old('price')}}" oninput="saveValue(this)" required>
            @error('price')
            <small class="form-error">{{ $message }}</small>
            @enderror
    
          </div>
  
          
  
          
    
          <div class="form-group">
            <label for="description">Product Description</label>
            <input id="description" type="hidden" name="description"  value="{{old('description')}}">
            <trix-editor input="description" id="desc" oninput="saveValue(this)"></trix-editor>


            {{-- <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="6" name="description" required>{{old('description')}}</textarea>
            @error('description')
            <small class="form-error">{{ $message }}</small>
            @enderror --}}
          </div>
          <div class="row">
            <div class="col-lg-10">
              <div class="form-group">
                <label for="category">Category</label>
                <select data-placeholder="Select a Category" name="category" id="category" class="form-control @error('category') is-invalid @enderror" onchange="saveValue(this)" required>
                  <option></option>
                  @foreach ($categories as $category)
                <option value="{{$category->id}}" @if (old('category') == $category->id) selected  @endif>{{$category->category_name}}</option>
                  @endforeach
                </select>
                @error('category')
                <small class="form-error">{{ $message }}</small>
                @enderror
              </div>
            </div>
     
              <div class="col-lg-2" style="margin-top: 28px;">
                <button onclick="addCategory()" type="button" class="btn btn-secondary"><i class="fa fa-plus"></i></button>
              </div>
   
          </div>




          
  
          <div class="row">
            <div class="col-lg-10">
              <div class="form-group">
                  <label for="size">Size</label>
                  <select data-placeholder="Select a Size" name="size" id="size" class="form-control @error('size') is-invalid @enderror" onchange="saveValue(this)" required>
                    <option></option>
                    @foreach ($sizes as $size)
                      <option value="{{$size->id}}" @if (old('size') == $size->id) selected  @endif>{{$size->name}}</option>
                    @endforeach
                  </select>
                  @error('size')
                  <small class="form-error">{{ $message }}</small>
                  @enderror
                </div>
            </div>
            <div class="col-lg-2" style="margin-top: 28px">
                <button onclick="addSize()" type="button" class="btn btn-info"><i class="fa fa-plus"></i></button>
            </div>
        </div>
        </div>
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-10">
              <div class="form-group">
                <label for="subcategory">Product Types</label>
                <select data-placeholder="--Select Product Types--" name="subcategory" id="subcategory" class="form-control @error('subcategory') is-invalid @enderror" onchange="saveValue(this)" required>
                  <option></option>
                  @foreach ($subcategory as $cat)
                  
                  <option value="{{$cat->id}}" @if (old('category') == $cat->id) selected  @endif>{{$cat->subcategory_name}}</option>
                
                  @endforeach
                </select>
                @error('subcategory')
                <small class="form-error">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="col-lg-2" style="margin-top: 28px">
              <button onclick="addSubcategory()" type="button" class="btn btn-dark"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-10">
    
          <div class="form-group">
            <label for="tags">Tag</label>
            <select data-placeholder="-Tags-" class="js-example-responsive" multiple="multiple" name="tags[]" id="tags" class="form-control @error('tags') is-invalid @enderror"  required>
              <option></option>
              @foreach ($tags as $tag)
            <option value="{{$tag->id}}" @if(old('tags') != null) @foreach(old('tags') as $single_tag) @if($single_tag == $tag->id) selected @endif  @endforeach @endif>{{$tag->tag_name}}</option>
              @endforeach
            </select>
            @error('tags')
            <small class="form-error">{{ $message }}</small>
            @enderror
          </div>
            </div>
            <div class="col-lg-2" style="margin-top: 28px">
              <button onclick="addTag()" type="button" class="btn btn-warning"><i class="fa fa-plus"></i></button>
            </div>
          </div>
    
          <div class="row">
            <div class="col-lg-10">
              <div class="form-group">
                <label for="brand">Brand</label>
                <select data-placeholder="--Select a Brand--" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror" name="brand" onchange="saveValue(this)" required>
                  <option></option>
                  @foreach ($brands as $brand)
                  <option value="{{$brand->id}}" @if (old('brand') == $brand->id) selected  @endif>{{$brand->brand_name}}</option>
                    @endforeach
                </select>
                @error('brand')
                <small class="form-error">{{ $message }}</small>
                @enderror
              </div>
            </div>
            <div class="col-lg-2" style="margin-top: 28px;">
              <button onclick="addBrand()" type="button" class="btn btn-primary"><i class="fa fa-plus"></i></button>
            </div>
          </div>



          <div class="form-group">
            <label for="image">Product Image</label>
          <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" required>
            @error('image')
            <small class="form-error">{{ $message }}</small>
            @enderror
    
          </div>
          <div class="form-group">
            <img style="padding: 10px;display: none" class="img-thumbnail rounded" src="" id="pd_image" alt="">
          </div>


          <div class="form-group">
            <strong>In Stock</strong> <br>
            <div class="onoffswitch">
              <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0" value="1" checked>
              <label class="onoffswitch-label" for="myonoffswitch">
                  <span class="onoffswitch-inner"></span>
                  <span class="onoffswitch-switch"></span>
              </label>
          </div>
      
          </div>

          <div class="form-group mt-5" style="border: 1px solid #ccc;padding: 10px;">

          <h5 class="mt-3 mb-3">Product Image Gallery</h5>
         
          <div class="input-group hdtuto control-group lst increment" >
            <input type="file" name="gallery_image[]" class="myfrm form-control">
            <div class="input-group-btn"> 
              <button class="btn btn-success" id="AddImage" type="button"><i class="fas fa-plus"></i></button>
            </div>
          </div>

          
          <div class="clone hide">
            <div class="hdtuto control-group lst input-group" style="margin-top:10px">
              <input type="file" name="gallery_image[]" class="myfrm form-control">
              <div class="input-group-btn"> 
                <button class="btn btn-danger" id="removeImage" type="button"><i class="fas fa-trash"></i></button>
              </div>
            </div>
          </div>
        </div>
          
        </div>
    
          <div class="col-lg-12">
            <div class="form-group">
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
          </div>
    
      
      </div>
    </form>
    </div>
  </div>
</div>
</div>


@endsection
@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/trix.css')}}">
@endpush

@push('js')
<script src="{{asset('public/assets/js/trix.js')}}"></script>
<script>
var trixedelement = document.querySelector("trix-editor");
if(getSavedValue("product_name") != ""){
  document.getElementById("product_name").value = getSavedValue("product_name");
}
if(getSavedValue("price") != ""){
document.getElementById("price").value = getSavedValue("price");
}

if(getSavedValue("desc") != ""){
  // set the value to this input
  $("desc").val(getSavedValue("desc"));
//trixedelement.editor.insertHTML(getSavedValue("desc")); // set the value to this input
}

$("#category").val(getSavedValue("category")).trigger('change'); 
$("#subcategory").val(getSavedValue("subcategory")).trigger('change'); 
$("#size").val(getSavedValue("size")).trigger('change'); 
$("#brand").val(getSavedValue("brand")).trigger('change'); 



/* Here you can add more inputs to set value. if it's saved */

//Save the value function - save it to sessionStorage as (ID, VALUE)
function saveValue(e){
    var id = e.id;  // get the sender's id to save it . 
    var val = e.value; // get the value. 
    sessionStorage.setItem(id, val);// Every time user writing something, the sessionStorage's value will override . 
}

//get the saved value function - return the value of "v" from sessionStorage. 
function getSavedValue  (v){
    if (!sessionStorage.getItem(v)) {
        return "";// You can change this to your defualt value. 
    }
    return sessionStorage.getItem(v);
}



// $(".add_modal_submit").click(function (e) {
//     //disable the submit button
//     //$('.add_modal_submit').attr("disabled", true);
//     $('.add_modal_submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading.....' );
//     return true;
// });


function reset(){
    sessionStorage.clear();
    $("#reset").html('<div class="fa-1x"><i class="fas fa-spinner fa-spin"></i> <b> Please Wait.....</b></div>');
    location.reload('true');
  }


function addCategory(){
  $("#categoryDataModal").modal('show');
}

function addSize(){
  $('#DataModal').modal('show');
}

function addTag(){
  $('#tagDataModal').modal('show');
}

function addSubcategory(){
  $('#SubcatDataModal').modal('show');
}
function addBrand(){
  $('#brandDataModal').modal('show');
}




// Show Current Image On the Form Before Upload



function ProductimageURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#pd_image').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}


$("#image").change(function() {
  $('#pd_image').show();
  ProductimageURL(this);
});






$("#AddImage").click(function(){ 
      var lsthmtl = $(".clone").html();
      $(".increment").after(lsthmtl);
  });
  $("body").on("click","#removeImage",function(){ 
      $(this).parents(".hdtuto.control-group.lst").remove();
  });








  var colors = ["#eb4d4b", "#A3CB38", "#f1c40f", "#f39c12", "#2980b9", "#ff7979", "purple"];
  //For Addmodal


    $('#size').select2({
      width: '100%',
      theme: "bootstrap"
    });


  //For Editmodal
  $('#category').select2({
      width: '100%',
      theme: "bootstrap"
    });
    $('#subcategory').select2({
      width: '100%',
      theme: "bootstrap"
    });
    $('#tags').select2({
      width: '100%',
      theme: "bootstrap",templateSelection: function (data, container) {
    $(container).css("background-color", colors[2]);
    $(container).css("color", "#ffffff");
    return data.text;
}
    });
    $('#brand').select2({
      width: '100%',
      theme: "bootstrap",
      
    });

</script>



@endpush