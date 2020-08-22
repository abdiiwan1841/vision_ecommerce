
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
<!--End Category Modal -->



<!--Size Modal -->
@component('component.common.modal')

    @slot('modal_id')
        DataModal
    @endslot
    @slot('submit_button')
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
    @slot('submit_button')
    add_modal_submit
    @endslot
    @slot('modal_title')
      Add Product Types
    @endslot

    @slot('modal_form') 
       <form action="{{route('subcategories.store')}}" method="POST" enctype="multipart/form-data" id="addSubcat">
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
      
    @endslot
@endcomponent



@component('component.common.modal')

    @slot('modal_id')
        tagDataModal
    @endslot
    @slot('submit_button')
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
    @slot('submit_button')
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
          <h5 class="card-title text-right">EDIT  PRODUCTS</h5>
        </div>
      </div>
         
    </div>
    <div class="card-body">
      @if(Session::has('success'))
      <div class="mt-3 mb-5">
      <p class="alert alert-success"><strong>Alert:</strong> {{Session::get('success')}}</p>
      </div>
      @endif

      
    <form action="{{route('products.update',$product->id)}}" method="POST" enctype="multipart/form-data">
     @csrf
     @method('PUT')
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="product_name">Product Name</label>
          <input type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name" id="product_name" placeholder="Enter Product Name" value="{{old('product_name',$product->product_name)}}" required>
            @error('product_name')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
    
          
  
          <div class="form-group">
            <label for="price">Product Price</label>
            <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" id="price" placeholder="Enter Price" value="{{old('price',$product->current_price)}}" required>
            @error('price')
            <small class="form-error">{{ $message }}</small>
            @enderror
    
          </div>
  
          
  
          
    
          <div class="form-group">




            <label for="description">Product Description</label>


            <input id="description" type="hidden" name="description" value="{{old('description',$product->description)}}">
            <trix-editor input="description"></trix-editor>

            {{-- <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="6" name="description" required >{{old('description',$product->description)}}</textarea>
            @error('description')
            <small class="form-error">{{ $message }}</small>
            @enderror --}}
          </div>
  
          <div class="row">
            <div class="col-lg-10">
              <div class="form-group">
                <label for="category">Category</label>
                <select data-placeholder="Select a Category" name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                  <option></option>
                  @foreach ($categories as $category)
                <option value="{{$category->id}}" @if ($product->category_id == $category->id) selected  @endif>{{$category->category_name}}</option>
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
                  <select data-placeholder="Select a Size" name="size" id="size" class="form-control @error('size') is-invalid @enderror" required>
                    <option></option>
                    @foreach ($sizes as $size)
                      <option value="{{$size->id}}" @if (old('size',$product->size_id) == $size->id) selected  @endif>{{$size->name}}</option>
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
        <div class="col-lg-6">
          <div class="row">
            <div class="col-lg-10">
              <div class="form-group">
                <label for="subcategory">Product Types</label>
                <select data-placeholder="--Select Product Types--" name="subcategory" id="subcategory" class="form-control @error('subcategory') is-invalid @enderror" required>
                  <option></option>
                  @foreach ($subcategory as $cat)
                  
                  <option value="{{$cat->id}}" @if (old('subcategory',$product->subcategory_id) == $cat->id) selected  @endif>{{$cat->subcategory_name}}</option>
                
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
            <select data-placeholder="-Tags-" class="js-example-responsive" multiple="multiple" name="tags[]" id="tags" class="form-control @error('tags') is-invalid @enderror" required>
              <option></option>
              @foreach ($tags as $tag)
            <option value="{{$tag->id}}"  @foreach($product->tags as $single_tag) @if($single_tag->id == $tag->id) selected @endif  @endforeach >{{$tag->tag_name}}</option>
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
                <select data-placeholder="--Select a Brand--" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror" name="brand" required>
                  <option></option>
                  @foreach ($brands as $brand)
                  <option value="{{$brand->id}}" @if (old('brand',$product->brand_id) == $brand->id) selected  @endif>{{$brand->brand_name}}</option>
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
          <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
            @error('image')
            <small class="form-error">{{ $message }}</small>
            @enderror
    
          </div>
          <div class="form-group">
          <img style="padding: 10px;e" class="img-thumbnail rounded" src="{{asset('public/uploads/products/thumb/'.$product->image)}}" id="pd_image" alt="">
          </div>

          <div class="form-group">
             <strong>In Stock</strong> <br>
            <div class="onoffswitch">
              <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0" value="1" @if($product->in_stock == 1) checked @endif>
              <label class="onoffswitch-label" for="myonoffswitch">
                  <span class="onoffswitch-inner"></span>
                  <span class="onoffswitch-switch"></span>
              </label>
          </div>
      
          </div>



          
        </div>
    
          <div class="col-lg-12">
            <div class="form-group">
              <button type="submit" class="btn btn-success">Update</button>
            </div>
          </div>
    
      
      </div>
    </form>

   
     
      @if(!empty($product->gallery_image))
      <div class="form-group">
      <h5>Previous Gallery Image</h5>
       @php $gallery_image_array = json_decode($product->gallery_image) @endphp
       
        @foreach ($gallery_image_array as $item)
      <form action="{{route('products.removegalleryimage',$product->id)}}" method="POST" style="display: inline-block;margin-right: 10px;border: 1px solid #ddd;padding: 10px;border-radius: 5px;">
        @csrf
       <input type="hidden" value="{{$item}}" name="gal_image">
        <img style="width: 70px;border-radius: 100%" src="{{asset('public/uploads/gallery/thumb/'.$item)}}" alt="">
        <button onclick="return confirm('Are You Sure You Want To Delete This Gallery Image?')" type="submit" class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i> </button>
        </form>
        @endforeach
      </div>
      @endif
   
    </div>
  </div>
</div>
<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      product Transfer
    </div>
    <div class="card-body">
      <h4><b>" {{$product->product_name}} " </b>  Is Currently In {!!showProductTypes($product->type)!!} Module</h4>

      @if($product->type === 'ecom')
      <form action="{{route('product.transfertoinventory',$product->id)}}" method="POST" class="mt-3">
        @csrf
        <input type="hidden" name="type" value="pos">
      <button onclick="return confirm('Are you sure you want to transfer this product to Inventory module?')" type="submit" class="btn btn-danger btn-sm">Move To Inventory Module</button>
      </form>
      @elseif($product->type === 'pos')

      <form action="{{route('product.transfertoecom',$product->id)}}" method="POST">
        @csrf
        <input type="hidden" name="type" value="ecom">
      <button onclick="return confirm('Are you sure you want to transfer this product to e-Commerce module?')" type="submit" class="btn btn-warning btn-sm">Move To E-Commerce Module</button>
      </form>

      @endif



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


function addSize(){
  $('#DataModal').modal('show');
}

function addCategory(){
  $("#categoryDataModal").modal('show');
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