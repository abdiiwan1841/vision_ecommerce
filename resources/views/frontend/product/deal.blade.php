@extends('layouts.frontendlayout')
@section('title','Special Deal')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="#"><i class="fa fa-home"></i> Home</a>
                    <span>Product</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->
<div class="container-fluid">
  <div class="row my-5">
    <div class="col-lg-6 offset-lg-3">


    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body">
          <div class="row ">
            <div class="col-12 col-sm-6">
            <h3 class="d-inline-block d-sm-none">{{$single_product->product_name}}</h3>
            
              <div class="col-12">
              <img src="{{asset('public/uploads/products/original/'.$single_product->image)}}" class="product-image" alt="Product Image">
              </div>

            </div>
            <div class="col-12 col-sm-6">
              <h3 class="my-3">{{$single_product->product_name}}</h3>
              
            <h5>Brand : <span class="badge badge-warning">{{$single_product->brand->brand_name}}</span></h5>
            
            
              <hr>



              <div class="bg-gray py-2 px-3 mt-4">
                <h3 class="mb-0">
                  Tk. {{$deal->amount}}
                </h3>
                <h4 class="mt-0 price_heading">
                  <del>Tk. {{$single_product->price}}</del>
                </h4>
              </div>

              <div class="mt-4">
               
              <a id="pd-{{$single_product->id}}" data-stock="{{$single_product->stock($single_product->id)}}" data-name="{{$single_product->product_name}}" data-image="{{asset('public/uploads/products/tiny/'.$single_product->image)}}" data-id="{{$single_product->id}}" data-price="{{$deal->amount}}" href="#" class="add-to-cart btn btn-cart"><i class=" icon_cart_alt"></i> Add To Cart</a>
                
               
              </div>

            </div>
            
          </div>

        </div>
        <div class="card-body">
          <p>{{$single_product->description}}</p>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
</div>
</div>
@endsection

@push('js')

<script>
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
             $('#pd-'+value.id).html('<i class="icon_check"></i> Added');
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
             $('#pd-'+id).html('<i class="icon_check"></i>  Added').css('background','#44bd32');
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
             $('#pd-'+cart[item].id).html('<i class="icon_cart_alt"></i> Add To Cart');
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