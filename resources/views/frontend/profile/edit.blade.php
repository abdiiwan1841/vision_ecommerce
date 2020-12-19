@extends('layouts.frontendlayout')
@section('title','User Profile')
@section('content')
    
  
<div class="container">
  <div class="row spad">
    <div class="col-lg-3">
      <ul class="list-group proflie-sidemenu  mb-5">
        <li class="list-group-item"><a href="{{route('orders.show')}}">My Order</a></li>
      <li class="list-group-item"> <a href="{{route('profile.show')}}">My Profile</a> </li>
        <li class="list-group-item current">Edit Profile</li>
      <li class="list-group-item"> <a href="{{route('profile.changepassword')}}">Change Password</a> </li>
      </ul>
    </div>
    <div class="col-lg-9">
    <form action="{{route('profile.update')}}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-lg-6">
            
            <div class="form-group">
                <label for="name">Name<span>*</span></label>
            <input type="text" id="name" placeholder="Enter Your Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name',Auth::user()->name)}}">
      
                @error('name')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>


      
            <div class="form-group">
                <label for="email">Email<span>*</span></label>
                <input type="text" id="email" placeholder="Enter Your Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email',Auth::user()->email)}}">
                @error('email')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
      
            <div class="form-group">
                <label for="phone">Phone<span>*</span></label>
            <input type="text" id="phone" placeholder="Enter Your phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{old('phone',Auth::user()->phone)}}">
                @error('phone')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
              <label for="image">Image (optional)</label>
          <input type="file" id="image" placeholder="Upload Your Profile Avatar" class="form-control @error('image') is-invalid @enderror" name="image">
              @error('image')
              <small class="form-error">{{ $message }}</small>
              @enderror


              <img class="mt-3 mb-3 img-thumbnail" src="{{asset('public/uploads/user/thumb/'.Auth::user()->image)}}" id="user_image" alt="">
          </div>

      

        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="address">Address<span>*</span></label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address"  rows="4" placeholder="Enter Your Addres">{{old('address',Auth::user()->address)}}</textarea>
                
                @error('address')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            
            <div class="form-group">
                <label for="division">Division<span>*</span></label>
                <select name="division" id="division" class="form-control @error('division') is-invalid @enderror">
                    <option value="">Select Division</option>
                    @foreach ($divisions as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                    
                </select>
                @error('division')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>


            

        </div>
        <div class="col-lg-8">
            <div class="form-group">
                <button type="submit" class="btn btn-success">UPDATE</button>
            </div>
        </div>
      
    </div>
  </form>
    </div>
  </div>
</div>


@endsection

@push('css')
<!-- Select 2 min  Css -->
<link href="{{asset('public/assets/css/select2.min.css')}}" rel="stylesheet" />
<!-- Select 2 Bootstrap  Css -->
<link href="{{asset('public/assets/css/select2-bootstrap.min.css')}}" rel="stylesheet" />

@endpush

@push('js')
<!-- Select 2 js -->
<script src="{{asset('public/assets/js/select2.min.js')}}"></script>

<script>
function uerImageURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#user_image').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}
$("#image").change(function() {
  uerImageURL(this);
  $('#user_image').show();
});
  
$('#division').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a Division",
});
$('#district').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a District",
});

$('#area').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a Area",
});
var exist_division_id = '{{Auth::user()->division_id}}';
var exist_district_id = '{{Auth::user()->district_id}}';
var exist_area_id = '{{Auth::user()->area_id}}';

$("#division").val(exist_division_id).trigger('change');
var district_id = $("#district").val();



    var base_url = '{{url('/')}}';
    var output = '';
    var division_id = $("#division").val();
    

    if(division_id.length > 0){
        $.get("{{asset('')}}api/district/"+division_id, function(data, status){
            if(data.length>0){
            output = '';
            $(data).each(function(index,element){
                if(element.id == exist_district_id){
                    output += '<option value="'+element.id+'" selected>'+element.name+'</option>';
               
            }else{
                output += '<option value="'+element.id+'">'+element.name+'</option>';
            }
            });
                $("#district").html(output);
            }else{
                $("#district").html('<option value="">No Area Found</option>');
            }
        });
        }else{
            $("#area").html('<option value="">No Area Found</option>');
        }



     $.get("{{asset('')}}api/area/"+exist_district_id, function(data, status){
        if(data.length>0){
        output = '';
        $(data).each(function(index,areaelement){
            if(areaelement.id == exist_area_id){

                output += '<option value="'+areaelement.id+'" selected>'+areaelement.name+'</option>';
            }else{
                output += '<option value="'+areaelement.id+'">'+areaelement.name+'</option>';
            }
        
        });
        $("#area").html(output);
        }else{
            $("#area").html('<option value="">No Area Found</option>');
        }
    });

        
    $("#division").change(function(){
        var division_id = $("#division").val();
        if(division_id.length > 0){
          $.get("{{asset('')}}api/district/"+division_id, function(data, status){
            if(data.length>0){
            output = '';
            $(data).each(function(index,element){
               output += '<option value="'+element.id+'">'+element.name+'</option>';
            });
               $("#district").html(output);
               $("#district").val(null).trigger('change');
               $('#area').html('');
 
            }else{
                $("#district").html('<option value="">No Area Found</option>');
            }
        });
        }else{
          $("#area").html('<option value="">No Area Found</option>');
        }
        });


       
    


       
        $("#district").change(function(){
      district_id = $("#district").val();
            
        if(district_id != null){
          $.get("{{asset('')}}api/area/"+district_id, function(data, status){
            if(data.length>0){
            output = '';
            $(data).each(function(index,element){
               output += '<option value="'+element.id+'">'+element.name+'</option>';
            });
               $("#area").html(output);
            }else{
                $("#area").html('<option value="">No Area Found</option>');
            }
        });
        }else{
          $("#area").html('<option value="">No Area Found</option>');
        }
        });






var cartLink = '{{route('cartpage.index')}}';
  var checkoutLink = '{{route('checkoutpage.index')}}';

function cancelOrder(id){
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
          confirmButton: 'btn btn-success btn-sm',
          cancelButton: 'btn btn-danger btn-sm'
      },
      buttonsStyling: true
      })

swalWithBootstrapButtons.fire({
title: 'Are you sure Want to Cancel  Order ID  #'+id+'?',
text: "Please Keep in mind You won't be able to revert this!",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Yes, Cancel it!'
}).then((result) => {
      if (result.value) {
          event.preventDefault();
          document.getElementById('cancel-'+id).submit();
      } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
      ) {
          swalWithBootstrapButtons.fire(
          'Cancelled',
          'Your Order  is safe :)',
          'error'
          )
      }
      });
  }


  
    
    function displayCart() {
      var cartArray = shoppingCart.listCart();
      var output = "";
      for(var i in cartArray) {
        output += "<tr>"
          +"<td class='si-pic'><img src='"+cartArray[i].image +"' alt=''></td>"
          + "<td class='si-text'><div class='product-selected'><p>"+cartArray[i].price+"x " + cartArray[i].count + "="+Math.round(cartArray[i].total)+" Tk</p><h6>"+cartArray[i].o_name +"</h6>" 
          +"</div></td>"
          + "<td class='si-close'><button class='delete-item btn' data-name=" + cartArray[i].name + "><i class='ti-close'></i></button></td>"
          +  "</tr>";
      }
      $('.show-cart').html(output);
      $('.cart-price').html(shoppingCart.totalCart() + 'Tk');
      $('.total-count').html(shoppingCart.totalCount());
      if(cart.length >0){
      $('.select-total').html('<span>total:</span><h5 class="total-cart">'+shoppingCart.totalCart()+'</h5>');
      $('.select-button').show();
      $('.select-button').html('<a href="'+cartLink+'" class="primary-btn view-card">VIEW CART</a><a href="'+checkoutLink+'" class="primary-btn checkout-btn">CHECK OUT</a>');
      }else{
        $('.select-button a').attr('disabled', true);
        $('.select-button').hide();
        $('.select-total').html('No Products On the Cart');
  
      }
  
    }



</script>
<script src="{{asset('public/assets/frontend/js/cart.js')}}"></script>
@endpush