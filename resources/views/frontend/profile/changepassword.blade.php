@extends('layouts.frontendlayout')
@section('title','User Profile')
@section('content')
    
  
<div class="container">
  <div class="row spad">
    <div class="col-lg-3">
      <ul class="list-group proflie-sidemenu mb-5">
        <li class="list-group-item"><a href="{{route('orders.show')}}">My Order</a></li>
        <li class="list-group-item"><a href="{{route('profile.show')}}">Profile</a></li>
      <li class="list-group-item"> <a href="{{route('profile.editprofile')}}">Edit Profile</a> </li>
      <li class="list-group-item current">Change Password</li>
      </ul>
    </div>
    <div class="col-lg-9">
        
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <h4 class="mb-3">Change Password</h4>

        <form action="{{route('profile.passupdate')}}" method="POST">
        @csrf
        @method('PUT')
        @if (Session::has('success'))
        <span class="help-block" style="color: green">
            <strong>{{ Session::get('success') }}</strong>
        </span>
    @endif
        <div class="form-group">
            <label for="old_password">Old Password</label>
        <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" value="{{old('old_password')}}">

        @error('old_password') <small class="form-error"> {{$message}} </small> @enderror

            @if (Session::has('old_password'))
            <span class="help-block" style="color: red">
                <small>{{ Session::get('old_password') }}</small>
            </span>
        @endif
        </div>
        <div class="form-group">
            <label for="password">New Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}" >


            @error('password') <small class="form-error"> {{$message}} </small> @enderror

            @if (Session::has('password'))
            <span class="help-block" style="color: red">
                <small>{{ Session::get('password') }}</small>
            </span>
        @endif
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="{{old('password_confirmation')}}">
            
            @error('password_confirmation') <small class="form-error"> {{$message}} </small> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
    </div>
  </div>
</div>


@endsection

@push('js')

<script>

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