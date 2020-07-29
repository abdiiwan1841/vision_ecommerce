@extends('layouts.frontendlayout')
@section('title','Contact Us')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{route('homepage.index')}}"><i class="fa fa-home"></i> Home</a>
                    <span>Contact</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

  <!-- Map Section Begin -->
  <div class="map spad">
    <div class="container">
          {!!$CompanyInfo->map_embed!!}

    </div>
</div>
<!-- Map Section Begin -->

<!-- Contact Section Begin -->
<section class="contact-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="contact-title">
                    <h4>Contacts Us</h4>
                    <p>Have Any questions please feel free to contact with us</p>
                </div>
                <div class="contact-widget">
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-location-pin"></i>
                        </div>
                        <div class="ci-text">
                            <span>Address:</span>
                        <p>{{$CompanyInfo->address}}</p>
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-mobile"></i>
                        </div>
                        <div class="ci-text">
                            <span>Phone:</span>
                            <p>{{$CompanyInfo->phone}}</p>
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-email"></i>
                        </div>
                        <div class="ci-text">
                            <span>Email:</span>
                            <p>{{$CompanyInfo->email}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <div class="contact-form">
                    @if(Session::has('success'))

                       <div class="alert alert-success">{{Session::get('success')}}</div>

                    @else
                    <div class="leave-comment">
                        <h4>Leave A Comment</h4>
                        <p>Our staff will call back later and answer your questions.</p>
                    <form action="{{route('contactpage.submit')}}" class="comment-form" method="POST">
                        @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                  <div class="form-group">
                                    <input type="text" @error('name') class="contact-form-error" @enderror placeholder="Your name" name="name" value="{{old('name')}}">
                                  @error('name') <span style="color: red">{{$message}}</span> @enderror
                                   
                                  </div>
                                    
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                      <input type="text" @error('email') class="contact-form-error" @enderror placeholder="Your email" name="email" value="{{old('email')}}">
                                      @error('email') <span style="color: red">{{$message}}</span> @enderror
                                    </div>
                                   
                                </div>
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <input type="text" @error('phone') class="contact-form-error" @enderror placeholder="Your phone" name="phone" value="{{old('phone')}}">

                                  @error('phone') <span style="color: red">{{$message}}</span> @enderror
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <div class="form-group">
                                  <input type="text" @error('address') class="contact-form-error" @enderror placeholder="Your Address" name="address" value="{{old('address')}}">
                                  
                                  @error('address') <span style="color: red">{{$message}}</span> @enderror
                                  
                                </div>
                               
                            </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                      <textarea @error('message') class="contact-form-error" @enderror placeholder="Your message" name="message">{{old('address')}}</textarea>

                                      @error('message') <span style="color: red">{{$message}}</span> @enderror
                                    </div>
                                   
                                    <button type="submit" class="site-btn">Send message</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->

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


@if($errors->any())

<script>
  Toast.fire({
  position: 'top-end',
  icon: 'error',
  title: 'There is Some problem in the Contact Form field',

})
</script>

@endif


@if(Session::has('success'))

<script>
  Toast.fire({
  position: 'top-end',
  icon: 'success',
  title: '{{Session::get('success')}}',

})
</script>

@endif

<script>

  var cartLink = '{{route('cartpage.index')}}';
  var checkoutLink = '{{route('checkoutpage.index')}}';
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
              title: 'This Product Is Out Of Stock'
            });
              return;
        }else{
            $('#pd-'+id).html('<i class="icon_check"></i>').css('background','#44bd32');
            var item = new Item(o_name,name, price, count, id,image,stock);
            cart.push(item);
            saveCart();
            Toast.fire({
              icon: 'success',
              title: 'Successfully Added To Cart'
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