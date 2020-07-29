@extends('layouts.adminlayout')
@section('title','Add New Inventory Product')

@section('modal')
<!--Insert Modal -->
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
       <form action="{{route('productsizes.store')}}" method="POST" id="addForm">
        @csrf
    @endslot

    

    @slot('modal_body')
      <div class="form-group">
        <label for="name">Size</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Enter Size" value="{{old('name')}}">
        @error('name')
       <small class="form-error">{{ $message }}</small>
       @enderror
      </div>
      
    @endslot
@endcomponent
<!--End Insert Modal -->
@endsection


@section('content')




<div class="row">
<div class="col-lg-12">
	<div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-6">
                    <a href="{{route('posproducts.index')}}" class="btn btn-info btn-sm"><i class="fa fa-angle-left"></i> back</a>
                </div>
                <div class="col-lg-6">
                    <h5 class="text-right">ADD NEW PRODUCT</h5>
                </div>
            </div>
        </div>
    <div class="card-body">
    <div class="row justify-content-center">
        <div class="col-lg-6">

    <form action="{{route('posproducts.store')}}" method="POST" enctype="multipart/form-data" style="border: 1px solid #ddd;padding: 20px;border-radius: 5px">
        @csrf
        <div class="form-group">
            <label for="product_name">Product Name</label>
          <input type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name" id="product_name" placeholder="Enter Product Name" value="{{old('product_name')}}">
            @error('product_name')
           <small class="form-error">{{ $message }}</small>
           @enderror
          </div>
    
    
          <div class="form-group">
            <label for="price">Product Price</label>
            <input placeholder="Enter Price" type="text" class="form-control @error('price') is-invalid @enderror" name="price" id="price" value="{{old('price')}}">
            @error('price')
            <small class="form-error">{{ $message }}</small>
            @enderror
    
          </div>
    
    
    
    
    
          <div class="row">
              <div class="col-lg-10">
                <div class="form-group">
                    <label for="size">Size</label>
                    <select data-placeholder="Select a Size" name="size" id="size" class="form-control @error('size') is-invalid @enderror">
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

      
       
    
    
        
    
    
    
    
          <div class="form-group">
            <label for="image">Product Image (optional)</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
            @error('image')
            <small class="form-error">{{ $message }}</small>
            @enderror
    
          </div>
    
          <div class="form-group">
            <img  style="padding: 10px;display:none" class="img-thumbnail rounded" src="" id="pd_image2" alt="">
          </div>
          <div class="form-group">
              <button type="submit" onclick="addProduct()" class="btn btn-success">submit</button>
          </div>
    
</form>
    </div>
	</div>
    </div>
  </div>
</div>
</div>


@endsection

@push('css')

@endpush


@push('js')

@if ($errors->any())
{{-- prevent The Modal Close If Any Error In the Form --}}
<script>
    if(sessionStorage.getItem("addSize") === 'true'){
    $('#DataModal').modal('show');
    }
</script>
@endif




<script>
function addProduct(){
    sessionStorage.setItem("addSize",false);
}

function addSize(){
  $('#addForm').trigger("reset");
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();
  $('#DataModal').modal('show');
  if (typeof(sessionStorage) !== "undefined") {
    sessionStorage.setItem("addSize",true);
  }
}
function addProductreadURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#pd_image2').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}


$("#image").change(function() {
  addProductreadURL(this);
  $('#pd_image2').show();
});



$('#size').select2({
width: '100%',
theme: "bootstrap"
});
</script>

@endpush