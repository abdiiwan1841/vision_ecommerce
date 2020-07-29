@extends('layouts.adminlayout')

@section('title','Product Price')
@section('modal')
<!--Insert Modal -->
@component('component.common.modal')

    @slot('modal_id')
        EditDataModal
    @endslot
    @slot('modal_size')
    modal-md
    @endslot


    @slot('submit_button')
    add_modal_submit
    @endslot
    @slot('modal_title')
      Edit Price
    @endslot

    @slot('modal_form') 
       <form action="" method="POST" id="addForm">
        @csrf
        @method('PUT')
    @endslot

    

    @slot('modal_body')

        <div id="product-price-information"></div>

          <div class="row">
            
            <div class="col-lg-4">
              <label for="decrease">Decrease by (%)</label>
              <input type="number" class="form-control" id="decrease">
            </div>
            <div class="col-lg-4">
              <label for="increase">Increase by (%)</label>
              <input type="number" class="form-control" id="increase">
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="price">Price</label>
              <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" id="price" placeholder="Enter New Price" value="{{old('price')}}">
                @error('price')
               <small class="form-error">{{ $message }}</small>
               @enderror
              </div>
            </div>


            <div class="col-lg-12 mt-3">
              <button  id="price-reset"  onclick="BacktToOriginal()" type="button" class="btn btn-link">Click Here To Set Original Price</button>
            </div>
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

          <h5 class="card-title text-left">CHANGE ALL PRODUCT PRICE</h5>


    </div>
    <div class="card-body table-responsive">
      
     
      <table class="table table-bordered table-striped table-hover mt-3" id="jq_datatables">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Image</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
    
        @foreach ($products as $key => $product)
        
            <tr>
            <td>{{$key+1}}</td>
                <td>{{$product['product_name']}}</td>
                <td> <img style="height: 50px;" class="img-responsive img-thumbnail" src="{{asset('public/uploads/products/thumb/'.$product['image'])}}" alt=""></td>
                <td> @if($product->discount_price == NULL)
                  Tk.{{$product->price}}
                  @else Tk.{{$product->discount_price}} 
                  <small> <del>Tk.({{$product->price}})</del></small>
                  @endif</td>
            <td>
            <button type="button" onclick="EditProcess('{{route('price.api',$product->id)}}','{{route('price.update',$product->id)}}')"  class="btn btn-primary btn-sm" ><i class="fas fa-edit"></i></button> 
         
              </td>
            </tr>
        @endforeach

         
          
        </tbody>
      </table>

    </div>
  </div>
</div>
</div>


@endsection
@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/dataTables.bootstrap4.min.css')}}">
@endpush

@push('js')

<script>
   $("#decrease").on("input", function(){
        $("#increase").val('');
        var current_price = sessionStorage.getItem('current_price');
        var inputted_price = $(this).val();

        if(inputted_price.length < 1){
          $("#price").val(current_price);
        }else if(parseFloat(inputted_price) > parseFloat(current_price)){
            alert('Discount Must Not Be Greater Than Actual Price');
            $( this ).val('');
            $("#price").val(current_price);
        }else{
          var decreasepercent = (parseFloat(current_price))*(parseFloat($(this).val())/100)
          var newprice = parseFloat(current_price)-decreasepercent;
        // Print entered value in a div box
        $("#price").val(newprice);
        }
    });

  $("#increase").on("input", function(){
      $("#decrease").val('');
      var current_price = sessionStorage.getItem('current_price');
      var inputted_price = $(this).val();

        if(inputted_price.length < 1){
          $("#price").val(current_price);
        }else{
          var increasepercent = (parseFloat(current_price))*(parseFloat($(this).val())/100)
          var newprice = parseFloat(current_price)+increasepercent;
        // Print entered value in a div box
        $("#price").val(newprice);
        }
  });


</script>







@if ($errors->any())
{{-- prevent The Modal Close If Any Error In the Form --}}
<script>
    $('#EditDataModal').modal('show');
    $('#addForm').attr('action', sessionStorage.getItem('update_url'));
</script>
@endif

<script>

function BacktToOriginal(){
  $("#decrease").val('');
  $("#increase").val('');
  $("#price").val(sessionStorage.getItem('original_price'));
  $("#price-reset").attr('disabled',true);
}


var baseurl = '{{url('/')}}';
function EditProcess(edit_url, update_url){
$(document).ready(function(){
  sessionStorage.clear();
//reset form
$('#addForm').trigger("reset");
  $(".is-invalid").removeClass("is-invalid");
  $(".form-error").remove();
// Go to Edit Mode If Click Edit Button

$.get(edit_url, function (data) {
    //Change Form Action
    $('#addForm').attr('action', update_url);
    //assign data
    if(data.current_price == null){
    $('#price').val(0);
    }else{
      $('#price').val(data.current_price);
    }
    var disc_amount = data.price-data.current_price;
    $("#product-price-information").html('<table class="table table-bordered"><tr><th>Product Name: </th><td>'+data.product_name+'</td></tr><tr><th>Image</th><td><img id="pd-img" src="'+baseurl+'/public/uploads/products/tiny/'+data.image+'" alt=""></td></tr><tr><th>Original Price</th><td>'+data.price+'</td></tr><tr><th>Discount Amount</th><td>'+disc_amount+'</td></tr><tr><th>Newly Reduced Price</th><td>'+data.current_price+'</td></tr><tr><th>Last Updated</th><td>'+data.updated_at+'</td></tr></table>');
    
    if(data.price == null){
    sessionStorage.setItem('original_price',0);
    }else{
      sessionStorage.setItem('original_price',data.price);
    }
    
    if(data.current_price == null){
    sessionStorage.setItem('current_price',0);
    }else{
      sessionStorage.setItem('current_price',data.current_price);
    }
    sessionStorage.setItem('update_url',update_url);
    $("#price-reset").attr('disabled',false);

    $('#EditDataModal').modal('show');
}) 
});
}
</script>

<!-- Success Alert After Product  Delete -->
@if(Session::has('success'))
<script>
Swal.fire({
  icon: 'success',
  title: '{{Session::get('success')}}',
  showConfirmButton: false,
  timer: 1500
})
</script>
@endif




<script src="{{asset('public/assets/js/datatables.min.js')}}"></script>
<script src="{{asset('public/assets/js/dataTables.bootstrap4.min.js')}}"></script>
<script>
$('#jq_datatables').DataTable();
</script>

@endpush