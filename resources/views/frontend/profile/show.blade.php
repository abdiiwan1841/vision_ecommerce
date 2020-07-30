@extends('layouts.frontendlayout')
@section('title','User Profile')
@section('content')
    
  
<div class="container">
  <div class="row spad">
    <div class="col-lg-3">
      <ul class="list-group proflie-sidemenu  mb-5">
      <li class="list-group-item"><a href="{{route('orders.show')}}">My Order</a></li>
      <li class="list-group-item current">My Profile</li>
      <li class="list-group-item"> <a href="{{route('profile.editprofile')}}">Edit Profile</a> </li>
      <li class="list-group-item"> <a href="{{route('profile.changepassword')}}">Change Password</a> </li>
      </ul>
    </div>
    <div class="col-lg-9">
      <table class="table table-striped table-borderless table-sm">
        <thead>
          <tr>
            <th>Photo</th>
          <td><img  width="100px" src="{{asset('public/uploads/user/thumb/'.$user->image)}}" alt=""></td>
          </tr>
          <tr>
            <th>Name : </th>
             <td>{{$user->name}}</td>
          </tr>
          <tr>
            <th>Email : </th>
            <td>{{$user->email}}</td>
          </tr>
            
          <tr>
            <th>Phone : </th>
            <td>{{$user->phone}}</td>
          </tr>
          <tr>
            <th>District : </th>
            <td>{{$user->district->name}}</td>
          </tr>
          <tr>
            <th>Area : </th>
            <td>{{$user->area->name}}</td>
          </tr>
          <tr>
            <th>Address : </th>
            <td>{{$user->address}}</td>
          </tr>
        </thead>
        <tbody>
        

        </tbody>
      </table>
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