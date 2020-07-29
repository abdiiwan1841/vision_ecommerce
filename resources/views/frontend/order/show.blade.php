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
      //Toater Alert 
const Toast = Swal.mixin({
    toast: true,
    position: 'bottom-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
</script>

@if(Session::has('cancel_confirmed'))

<script>
  Toast.fire({
  icon: 'success',
  title: '{{Session::get('cancel_confirmed')}}'
  });
</script>
@endif


<script>

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












    /*------------------
        CountDown
    --------------------*/

   

    // For demo preview
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    if(mm == 12) {
        mm = '01';
        yyyy = yyyy + 1;
    } else {
        mm = parseInt(mm) + 1;
        mm = String(mm).padStart(2, '0');
    }
    var timerdate = mm + '/' + dd + '/' + yyyy;
    // For demo preview end
    var timerdate = "2020/05/03";

    console.log(timerdate);

      // Use this for real timer date
  






 // ************************************************
  // Shopping Cart API
  // ************************************************

  var cartLink = '{{route('cartpage.index')}}';
  var checkoutLink = '{{route('checkoutpage.index')}}';
 
   function displayCart() {
       var cartArray = shoppingCart.listCart();
       var output = "";
       for(var i in cartArray) {
         output += "<tr>"
           +"<td class='si-pic'><img src='"+cartArray[i].image +"' alt=''></td>"
           + "<td class='si-text'><div class='product-selected'><p>"+cartArray[i].price+"x " + cartArray[i].count + "="+cartArray[i].total+" Tk</p><h6>"+cartArray[i].o_name +"</h6>" 
           +"</div></td>"
           + "<td class='si-close'><button class='delete-item btn' data-name=" + cartArray[i].name + "><i class='ti-close'></i></button></td>"
           +  "</tr>";
       }
       $('.show-cart').html(output);
       $('.total-cart').html(shoppingCart.totalCart());
       $('.cart-price').html(shoppingCart.totalCart() + 'Tk');
       $('.total-count').html(shoppingCart.totalCount());
       if(cart.length >0){
       $('.select-button').show();
       $('.select-button').html('<a href="'+cartLink+'" class="primary-btn view-card">VIEW CART</a><a href="'+checkoutLink+'" class="primary-btn checkout-btn">CHECK OUT</a>');
       }else{
         $('.select-button a').attr('disabled', true);
         $('.select-button').hide();
   
       }
   
     }

  
  var shoppingCart = (function() {
      // =============================
      // Private methods and propeties
      // =============================
      cart = [];
      
      // Constructor
      function Item(o_name,name, price, count, id,image,stock) {
        this.o_name    = o_name;
        this.name = name;
        this.price = price;
        this.count = count;
        this.id    = id;
        this.image    = image;
        this.stock    = stock;
        
      }
      
      // Save cart
      function saveCart() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
          data: {
            'ShoppingCart' : cart,
          },
          url: "{{route('session.push')}}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              //console.log(data);
          },
          error: function (data) {
            //console.log('Error:', data);
              
          }
      });

      }
      


      $.get('{{route('session.getdata')}}',function(data){
      
        if(data.length > 0){
            cart = data;
              //Check If a Product Added And Mark the Product
            $.each(cart,function( index, value ) {
            $('#pd-'+value.id).html('<i class="icon_check"></i>');
            $('#pd-'+value.id).css('background','#44bd32');
            });
  
            displayCart();
        }
                //console.log(cart);
        
      });
      
    
      // =============================
      // Public methods and propeties
      // =============================
      var obj = {};
      
      // Add to cart
      obj.addItemToCart = function(o_name,name, price, count, id,image,stock) {
        for(var item in cart) {
          if(cart[item].name === name) {
            cart[item].count ++;
            saveCart();
            return;
          }
        }
        var item = new Item(o_name,name, price, count, id,image,stock);
        cart.push(item);
        saveCart();
      }
  
  
      obj.IncrementCart = function(o_name,name, price, count, id,image,stock) {
        for(var item in cart) {
          if(cart[item].name === name) {
            Toast.fire({
              icon: 'error',
              title: 'Product Already Added To cart'
            });
  
            return;
          }
        }
        if(stock < 1){
                            Swal.fire({
                icon: 'error',
                title: 'Stock Out',
                text: 'This Product Is Out Of Stock ',
                showConfirmButton: false,
                timer: 1000
                })
              return;
        }else{
            $('#pd-'+id).html('<i class="icon_check"></i>').css('background','#44bd32');
            var item = new Item(o_name,name, price, count, id,image,stock);
            cart.push(item);
            saveCart();
                        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Successfully Added To Cart',
            showConfirmButton: false,
            timer: 1500
            });
        }


      
      }
    
    
    
      
      // Set count from item
      obj.setCountForItem = function(name, count) {
        for(var i in cart) {
          if (cart[i].name === name) {
            cart[i].count = count;
            break;
          }
        }
      };
      // Remove item from cart
      obj.removeItemFromCart = function(name) {
          for(var item in cart) {
            if(cart[item].name === name) {
              cart[item].count --;
              if(cart[item].count === 0) {
                cart.splice(item, 1);
              }
              break;
            }
        }
        saveCart();
      }
    
      // Remove all items from cart
      obj.removeItemFromCartAll = function(name) {
        for(var item in cart) {
          if(cart[item].name === name) {
            Toast.fire({
              icon: 'success',
              title: cart[item].name+' Removed Successfully'
            });
            $('#pd-'+cart[item].id).html('<i class="icon_cart_alt"></i>');
            $('#pd-'+cart[item].id).css('background','#12CBC4');
            cart.splice(item, 1);
            
            break;
          }
        }
        saveCart();
      }
    
      // Clear cart
      obj.clearCart = function() {
        cart = [];
        saveCart();
      }
    
      // Count cart 
      obj.totalCount = function() {
        var totalCount = 0;
        for(var item in cart) {
            totalCount ++;
          //totalCount += cart[item].count;
        }
        return totalCount;
      }
    
      // Total cart
      obj.totalCart = function() {
        var totalCart = 0;
        for(var item in cart) {
          totalCart += cart[item].price * cart[item].count;
        }
        return Number(totalCart.toFixed(2));
      }
    
      // List cart
      obj.listCart = function() {
        var cartCopy = [];
        for(i in cart) {
          item = cart[i];
          itemCopy = {};
          for(p in item) {
            itemCopy[p] = item[p];
    
          }
          itemCopy.total = Number(item.price * item.count).toFixed(2);
          cartCopy.push(itemCopy)
        }
        return cartCopy;
      }
    

      return obj;
    })();



  
  
 
  
    
    // *****************************************
    // Triggers / Events
    // ***************************************** 
    // Add item
    $('.add-to-cart').click(function(event) {
      event.preventDefault();
     $('.cart-hover').show().delay(5000).fadeOut();
      var stock = $(this).data('stock');
      var o_name = $(this).data('name');
      var name = o_name.replace(/\s/g, '');
      var price = Number($(this).data('price'));
      var id = Number($(this).data('id'));
      var image = $(this).data('image');
      shoppingCart.IncrementCart(o_name,name, price, 1,id,image,stock);
      displayCart();
    });
    
    // Clear items
    $('.clear-cart').click(function() {
      shoppingCart.clearCart();
      displayCart();
    });
    
    



    
    // Delete item button
    
    $('.show-cart').on("click", ".delete-item", function(event) {
      var name = $(this).data('name')
      shoppingCart.removeItemFromCartAll(name);
      displayCart();
    })
    
    
    // -1
    $('.show-cart').on("click", ".minus-item", function(event) {
      var name = $(this).data('name')
      shoppingCart.removeItemFromCart(name);
      displayCart();
    })
    // +1
    $('.show-cart').on("click", ".plus-item", function(event) {
      var name = $(this).data('name')
      shoppingCart.addItemToCart(name);
      displayCart();
    })
    
    // Item count input
    $('.show-cart').on("change", ".item-count", function(event) {
       var name = $(this).data('name');
       var count = Number($(this).val());
      shoppingCart.setCountForItem(name, count);
      displayCart();
    });
    
    displayCart();
  
  
  




</script>
@endpush