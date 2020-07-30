@extends('layouts.frontendlayout')
@section('title','Order')
@section('content')
    
  
<div class="container">
  <div class="row spad">
    <div class="col-lg-3">
        <ul class="list-group proflie-sidemenu  mb-5">
        <li class="list-group-item current">My Order</li>
        <li class="list-group-item"><a href="{{route('profile.show')}}">My Profile</a></li>
        <li class="list-group-item"> <a href="{{route('profile.editprofile')}}">Edit Profile</a> </li>
        <li class="list-group-item"> <a href="{{route('profile.changepassword')}}">Change Password</a> </li>
        </ul>
       

    </div>
    <div class="col-lg-9">

      @foreach ($orders as $key =>  $item)
      <h4 class="mb-5 mt-5 text-center">ORDER ID: #{{$item->id}}</h4>
      <div class="row">
        <div class="col-lg-6">
          <table class="table table-borderless">
            <tr>
              <th>Order Date: </th>
              <td>{{$item->ordered_at->format('d-M-Y g:i A')}}</td>
            </tr>
            <tr>
              <th>Order ID:</th>
              <td>#{{$item->id}}</td>
            </tr>
            <tr>
              <th>Order Status: </th>
              <td>{!!FashiOrderStatus($item->order_status) !!}</td>
            </tr>

            <tr>
              <th>Estimated Delivery Date: </th>
              <td>@php $estimated_delivery_date = strtotime($item->shipping_date) @endphp {{date('d-M-Y',$estimated_delivery_date)}}</td>
            </tr>

            @if($item->payment_status == 0)
            <tr>
              <th>Payment Status: </th>
              <td>{!!FashiPaymentStatus($item->payment_status) !!}</td>
            </tr>
            @endif

            @if($item->shipping_status == 0)
            <tr>
              <th>Shipping Status: </th>
              <td>{!!FashiShippingStatus($item->shipping_status) !!}</td>
            </tr>
            @endif
        </table>
        </div>
        <div class="col-lg-6">
          @if($item->order_status == 0)
          <table class="table table-bordered">
              <tr>
                <th>Action</th>
                <td>

                <form id="cancel-{{$item->id}}" onclick="cancelOrder({{$item->id}})" action="{{route('order.orderdestory',$item->id)}}" method="POST">
                  @csrf
                      <button type="button" class="btn btn-danger">CANCEL THIS ORDER</button>
                  </form>
                  
                  
              </tr>
          </table>
          @endif

          @if($item->payment_status == 1)
                 
         
            <table class="table table-hover table-bordered">
              <tr>
                <th>Payment Status</th>
                <td> <img style="width: 100px" class="img-fluid" src="{{asset('public/assets/images/paid.png')}}" alt=""></td>
              </tr>
            <tr>
              <th>Cash: </th>
            <td>{{$item->cash}}</td>
            </tr>
            <tr>
              <th>Cash Updated Date:</th>
              <td>{{\Carbon\Carbon::parse($item->paymented_at)->format('d-m-Y g:i a')}}</td>
            </tr>

            
            @if($item->shipping_status == 1)
        
            <tr>
              <th>Shipping Status:</th>
              <td><img style="width: 200px" class="img-fluid" src="{{asset('public/assets/images/shipped.png')}}" alt=""></td>
            </tr>
            <tr>
              <th>Shipped At:</th>
              <td>{{$item->shipped_at->format('d-m-Y') }}</td>
            </tr>

            @endif
          </table>
     
            @endif

    

        </div>
      </div>

      @if($item->order_status == 2)

      <img style="width: 200px" src="{{asset('public/assets/images/canceled.jpg')}}" >
      

      @else
      <p style="text-align: center">PRODUCT DETAILS</p>
      <table class="table table-bordered table-hover table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Product Name</th>
            <th scope="col">Image</th>
            <th scope="col">Qty</th>
            <th scope="col">Price</th>
            <th scope="col">Total</th>
          </tr>
        </thead>
        <tbody>
          @php 
          $sum = 0;

          @endphp
          @foreach ($item->product as $key => $order_product)
          @php
              $pd_qty = $order_product->pivot->qty;
              $pd_price = $order_product->pivot->price;
              $pd_total = $pd_qty*$pd_price;
              $sum = $sum+$pd_total;
          @endphp
          <tr>
            <th class="align-middle">{{$key+1}}</th>
            <td class="align-middle">{{$order_product->product_name}}</td>
            <td class="align-middle"> <img src="{{asset('public/uploads/products/tiny/'.$order_product->image)}}" alt=""></td>
            <td class="align-middle">{{$pd_qty}}</td>
            <td class="align-middle">{{$pd_price}}</td>
            <td class="align-middle">{{$pd_total}}</td>
          </tr>

          @endforeach

        </tbody>
      </table>
      


      
      <div class="row">

        <div class="col-lg-7"></div>
        <!-- /.col -->
        <div class="col-5">
          <div class="table-responsive">
            <table class="table table-borderless">
              <tr>
                <th style="width:50%">Subtotal:</th>
              <td>{{$sum}}</td>
              </tr>
              <tr>
                <th>Discount ({{$item->discount}}%)</th>
              <td>
                @php
                    $discount = $sum*($item->discount/100);
                    echo "-".$discount;
                @endphp
                </td>
              </tr>
              <tr>
                <th>Taxable Amount</th>
              <td>
                @php
                    $taxablecash = $sum-$discount;
                    echo $taxablecash;
                @endphp
                </td>
              </tr>
              <tr>
                <th>Shipping</th>
              <td>
                @php
                    $shipping = $item->shipping;
                    echo "+".$shipping;
                @endphp
                </td>
              </tr>
              <tr>
                <th>Vat ({{$item->vat}}%)</th>
              <td>
                @php
                    $vat = $taxablecash*($item->vat/100);
                    echo "+".$vat;
                @endphp
                </td>
              </tr>
              <tr>
                <th>Tax ({{$item->tax}}%)</th>
              <td>
                @php
                    $tax = $taxablecash*($item->tax/100);
                    echo "+".$tax;
                @endphp
                </td>
              </tr>
             
              <tr>
                <th>Total:</th>
                <td>{{$grandToatl =  round($taxablecash+$vat+$tax+$shipping)}}</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>

      @endif
     
    @endforeach

 

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

@if(Session::has('cancel_confirmed'))

<script>
  Toast.fire({
  icon: 'success',
  title: '{{Session::get('cancel_confirmed')}}'
  });
</script>
@endif


@endpush