@extends('layouts.frontendlayout')

@section('content')
<div class="container py-2">
  <h4 class="text-center mt-5">CUSTOMER LOGIN</h4>
    <div class="row justify-content-center py-5">
     
        <div class="col-md-8 shadow p-0">
          
            <div class="row">
             
                <div class="col-md-5 p-0">
                 
                    <div id="login-form">
                     
                     
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            @if(Session::has('success'))
                            <div class="form-group row">
                            <span class="alert alert-success">{{Session::get('success')}}</span>
                            </div>
                            @endif
                            
                            <div class="form-group row pt-2">
                              
                                <div class="col-md-12">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" placeholder="{{__('E-Mail Address')}}" required
                                        autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                               
                                <div class="col-md-12">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password" placeholder="{{__('Password')}}">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>

                            <div class="form-group row">
                              
                                <div class="col-md-6">
                                    <div class="form-check pt-2">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-danger float-right">
                                        {{ __('Sign In') }}
                                    </button>

                                </div>
                            </div>

                            <div class="form-group row pt-3">
                             

                                {{-- <div class="col-md-8">
                                    @if (Route::has('password.request'))
                                    <a class="float-right " href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                    @endif
                                </div> --}}
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-7 p-0 login-image">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link href="{{ asset('public/assets/css/login.css') }}" rel="stylesheet">
@endpush
@push('js')
<script>
    // ************************************************
     // Shopping Cart API
     // ************************************************
   
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
         $('.select-button').html('<a href="shop/cart" class="primary-btn view-card">VIEW CART</a><a href="shop/cart/checkout" class="primary-btn checkout-btn">CHECK OUT</a>');
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
               Toast.fire({
                   icon: 'error',
                   title: 'Stock Out'
                 });
                 return;
           }else{
               $('#pd-'+id).html('<i class="icon_check"></i>').css('background','#44bd32');
               var item = new Item(o_name,name, price, count, id,image,stock);
               cart.push(item);
               saveCart();
               Toast.fire({
                 icon: 'success',
                 title: 'SuccessFully Added To Cart'
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