@extends('layouts.frontendlayout')
@section('title',$single_product->product_name)
@section('content')

@php
$current_stock = $single_product->stock($single_product->id);    

@endphp
<div class="container">
<div class="row">
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="#"><i class="fa fa-home"></i> Home</a>
                    <span>Single Product</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->
<div class="container-fluid">
  <div class="row my-5">
    <div class="col-lg-9">


    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body">
          <div class="row ">
            <div class="col-lg-6">
           
            
              <a style="cursor: crosshair" class="gallery-popup" href="{{asset('public/uploads/products/original/'.$single_product->image)}}"><img src="{{asset('public/uploads/products/original/'.$single_product->image)}}" class="product-image" alt="{{$single_product->product_name}}"></a>
              
              @if(!empty($single_product->gallery_image))
              <h5 class="mt-3">Product Gallery </h5>
              <hr>
              <ul class="gallery_image">
               
                @php
                $gallery_image_array = json_decode($single_product->gallery_image) @endphp
                @foreach($gallery_image_array as $single_image)
                <li> <a class="gallery-popup" href="{{asset('public/uploads/gallery/'.$single_image)}}"><img style="width:115px;" class="img-thumbnail" src="{{asset('public/uploads/gallery/thumb/'.$single_image)}}" class="product-image" alt="Product Image"></a> </li>
                @endforeach

               

              </ul>
              @endif
          

            </div>
            <div class="col-lg-6">
              <h3 class="my-3">{{$single_product->product_name}}</h3>
              
              <table class="table table-bordered">
                  <tr>
                    <th>Product Brand:</th>
                    <th>{{$single_product->brand->brand_name}}</th>
                  </tr>
                  <tr>
                    <th>Product Collection:</th>
                    <th>{{$single_product->category->category_name}}</th>
                  </tr>
                  <tr>
                    <th>Product Type:</th>
                    <th>{{$single_product->subcategory->subcategory_name}}</th>
                  </tr>
                  <tr>
                    <th>Product Size:</th>
                    <th>{{$single_product->size->name}}</th>
                  </tr>
              </table>
          
            
            
              <hr>



              <div class="product-price py-2 px-3">

  
                  @if($single_product->discount_price == NULL)
                  <h3 class="mb-0">
                  Tk.{{$single_product->price}}
                 </h3>
                  @else 
                  <h4 class="mt-0 price_heading">
                    <table class="table table-borderless">
                      <th>PRICE:</th>
                      <th style="color: #ff7979">Tk.{{$single_product->discount_price}} 
                        <small>Tk.{{$single_product->price}}</small></th>
                    </table>
                  
                  
                </h4>
                  @endif
     
              </div>

              <div>
                @if($current_stock < 1)
                  <span class="badge badge-danger">Sorry ! This Product Out Of Stock</span> 
                 
                @else 
                <a id="pd-{{$single_product->id}}" data-stock="{{$current_stock}}" data-name="{{$single_product->product_name}}" data-image="{{asset('public/uploads/products/tiny/'.$single_product->image)}}" data-id="{{$single_product->id}}" data-price="@if($single_product->discount_price == NULL) {{ $single_product->price}} @else {{ $single_product->discount_price}} @endif" href="#" class="add-to-cart site-btn"><i class=" icon_cart_alt"></i> Add To Cart</a>
                @endif
                
               
              </div>
              
            </div>
            
          </div>

        </div>
        <div class="card-body">
          <div class="pb-text">
            <h5><strong>Product Description:</strong></h5> <br>
            {!!$single_product->description!!}
          </div>

          <h5 class="mt-5"><strong>Comments:</strong></h5> <br>
          <div class="posted-by">
          @if(count($single_product->comments($single_product->id)) > 0)
          @foreach($single_product->comments($single_product->id) as $comment)
          
            <div class="pb-pic">
            <img style="width: 50px;" src="{{asset('public/assets/images/user.png')}}" alt="">
            </div>
            <div class="pb-text">
    
                <h5>{{$comment->name}}  </h5>
                <p style="color: #bbb"><small>{{$comment->created_at->format('d-M-Y g:i a')}}</small></p>
             
                <p>{{$comment->comments}}</p>
            </div>
        
        @endforeach
        @else
        <p>No Comments Found For This Product</p>
        @endif
      </div>
        <div class="leave-comment">
        @if(Session::has('success'))
        <div class="mt-5 mb-5">
      <span class="alert alert-success">{{Session::get('success')}}</span>
    </div>
      @else
          
            <h4 class="mb-5 mt-5">Leave A Comment</h4>
          <form action="{{route('comments.store',$single_product->id)}}" method="POST" class="comment-form">
            @csrf    
            <div class="row">
              @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                    <div class="col-lg-6">
                        <input type="text" placeholder="Name" name="name" required>
                    </div>
                    <div class="col-lg-6">
                        <input type="email" placeholder="Email" name="email" required>
                    </div>
                    <div class="col-lg-12">
                        <textarea placeholder="Messages" name="message" required></textarea>
                        <button type="submit" class="site-btn">Submit</button>
                    </div>
                </div>
            </form>
        
        @endif
      </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>

  <div class="col-lg-3">
      @if(count($simiar_product) > 0)
      <h4 class="mb-3 text-center text-uppercase">Similar Product</h4>
      @foreach ($simiar_product as $single_product)
          @php
            $similiar_product_stock = $single_product->stock($single_product->id);
          @endphp
              <div class="product-item">
                  <div class="pi-pic">
                      <img src="{{asset('public/uploads/products/thumb/'.$single_product->image)}}" alt="{{$single_product->product_name}}">
                  
                      @if($similiar_product_stock < 1)

                      <div style="background: #EA2027" class="sale pp-sale">Stock Out</div>
                      @elseif($similiar_product_stock > 50)
                       <div class="sale"> 50+ Available</div>
                       @else
                       <div class="sale"> {{$similiar_product_stock}} Available</div>

                      @endif
                      <div class="icon">
                        <span class="badge badge-pink">{{$single_product->brand->brand_name}}</span> 
                    </div>
                 
          
                      <ul>
             
                          <li class="quick-view"><a href="{{route('singleproduct.index',$single_product->id)}}">+ View Details</a></li>
                          
                      </ul>
                    </div>
               
                  <div class="pi-text">
                      <div class="catagory-name">{{$single_product->subcategory->subcategory_name}}</div>
                      <a href="#">
                          <h5>{{$single_product->product_name}}</h5>
                      </a>
                      <div class="product-price">
                        @if($single_product->discount_price == NULL)
                        Tk.{{$single_product->price}}
                        @else Tk.{{$single_product->discount_price}}
                        <span>Tk.{{$single_product->price}}</span>
                        @endif
                      </div>
                  </div>

              </div>
          @endforeach
          @endif
  </div>
</div>
</div>
</div>
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/util.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/frontend/css/magnific-popup.css')}}">

@endpush

@push('js')
<script src="{{asset('public/assets/frontend/js/jquery.magnific-popup.min.js')}}"></script>
<script>
  $('.gallery-popup').magnificPopup({
    type: 'image',
    mainClass: 'mfp-with-zoom', // this class is for CSS animation below

zoom: {
  enabled: true, 

  duration: 400,
  easing: 'ease-in-out', 

  opener: function(openerElement) {

    return openerElement.is('img') ? openerElement : openerElement.find('img');
  }
}
    // other options
    });

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