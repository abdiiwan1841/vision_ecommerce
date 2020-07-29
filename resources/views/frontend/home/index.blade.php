@extends('layouts.frontendlayout')
@section('title','Home')
@section('content')
@php
$colors = ["#eb4d4b", "#A3CB38", "#f1c40f", "#f39c12", "#2980b9", "#ff7979", "purple","#badc58","#78e08f","#079992"];      
@endphp


  
    <!-- Hero Section Begin -->
    <section class="hero-section">
        <div class="hero-items owl-carousel">
            @foreach ($sliders as $slider)
            <div class="single-hero-items set-bg" data-setbg="{{asset('public/uploads/sliders/scaled/'.$slider->image)}}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                        
                        <h1 @if($slider->title_color != NULL) style="color:{{$slider->title_color}}" @endif>{{$slider->title}}</h1>
                            <p @if($slider->description_color != NULL) style="color:{{$slider->description_color}}" @endif>{{$slider->description}}</p>
                            <a style="background: {{$slider->button_color}} " href="{{$slider->button_link}}" class="primary-btn">{{$slider->button_text}}</a>
                        </div>
                    </div>
                    @if($slider->box_status == 1)
                <div class="off-card" style="background: {{$slider->box_color}}">
                        <h2>{{$slider->box_text}}</h2>
                    </div>
                    @endif
                    
                </div>
            </div>
            @endforeach

        </div>
    </section>
    <!-- Hero Section End -->


    @if($deal != null)
    <!-- Deal Of The Week Section Begin-->

<section class="deal-of-week set-bg spad" style="background: {{$deal->bg_color}}">
<div class="container">
<div class="section-title">
  <h2>Current Offers</h2>
</div>
<div class="row">
<div class="col-lg-6">
  <img src="{{asset('public/uploads/deals/'.$deal->image)}}" alt="{{$deal->title}}">
</div>
  <div class="col-lg-6 text-center">
      <div class="section-title">
          <h2>{{$deal->title}}</h2>
          <p>{{$deal->description}}</p>
          <div class="product-price">
              Tk. {{$deal->amount}}
              <span>/ {{$deal->dealproduct->product_name}}</span>
          </div>
      </div>
      <div class="countdown-timer" id="countdown">
          <div class="cd-item">
              <span>56</span>
              <p>Days</p>
          </div>
          <div class="cd-item">
              <span>12</span>
              <p>Hrs</p>
          </div>
          <div class="cd-item">
              <span>40</span>
              <p>Mins</p>
          </div>
          <div class="cd-item">
              <span>52</span>
              <p>Secs</p>
          </div>
      </div>
      <a style="background: {{$deal->button_color}}" href="{{route('singleproduct.deal',$deal->product)}}" class="primary-btn">{{$deal->button_text}}</a>
  </div>
</div>
</div>
</section>
<!-- Deal Of The Week Section End -->

@endif




      <div class="row">
        <div class="col-lg-6">

        
        <!-- Banner Section Begin -->
        <div class="banner-section spad">
          <div class="container-fluid">
            <div class="section-title ">
              <h2>Product Types</h2>
            </div>
              <div class="row">
               
                  @foreach ($subcategories as $subcat)
                  
                  <div class="col-lg-6">
                  <a href="{{route('shoppage.subcategory',$subcat->id)}}">
                      <div class="single-banner">
                      <img class="img_leaf_shape" src="{{asset('public/uploads/product_type/frontend/'.$subcat->image)}}" alt="{{$subcat->subcategory_name}}">
                          <div class="inner-text">
                          <h5>{{$subcat->subcategory_name}} <span class="badge badge-danger">{{count(DB::table('products')->where('type','ecom')->where('subcategory_id',$subcat->id)->select('id')->get())}}</span></h5>
                          </div>
                      </div>
                    </a>
                  </div>
                
                  @endforeach
              </div>
          </div>
      </div>



      
              <!-- Brand  Section Begin -->
              <div class="banner-section no-pad-top">
                <div class="container-fluid">
                  <div class="section-title">
                    <h2>Brands</h2>
                  </div>
                    <div class="row">
                        @foreach ($brands as $brand)
                        
                        <div class="brand-list">
                        <a href="{{route('shoppage.brand',$brand->id)}}">
                            <div class="single-banner brand">
                           
                                <div class="inner-text">
                                <h5>{{$brand->brand_name}} <span class="badge badge-warning">{{count(DB::table('products')->where('type','ecom')->where('brand_id',$brand->id)->select('id')->get())}}</span></h5>
                                </div>
                            </div>
                          </a>
                        </div>
                      
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Brand Section End -->


    </div>

    
    <div class="col-lg-6">
      <div class="product-list banner-section spad">
        <div class="container-fluid">
          <div class="section-title">
            <h2>New Product</h2>
          </div>
        <div class="row">
          
            @foreach ($new_products as $single_product)
            @php
            $pd_stock = $single_product->stock($single_product->id);
            @endphp
            <div class="col-lg-6 col-sm-6">
                <div class="product-item">
                  
                    <div class="pi-pic">
                    <img src="{{asset('public/uploads/products/thumb/'.$single_product->image)}}" alt="{{$single_product->product_name}}">
                        <div class="sale">Brand New</div>
                        
                        @if($pd_stock < 1)
    
                        <div class="stock-out">Stock Out</div>
                        @endif
                        <div class="icon">
                          <span class="badge badge-pink">{{$single_product->brand->brand_name}}</span> 
                      </div>
                   
            
                        <ul>
  
                       
                        <li class="w-icon active"><a id="pd-{{$single_product->id}}" data-stock="{{$single_product->stock($single_product->id)}}" data-name="{{$single_product->product_name}}" data-image="{{asset('public/uploads/products/tiny/'.$single_product->image)}}" data-id="{{$single_product->id}}" data-price="@if($single_product->discount_price == NULL) {{ $single_product->price}} @else {{ $single_product->discount_price}} @endif" href="#" class="add-to-cart"><i class=" icon_cart_alt"></i></a></li>
                 
  
  
  
                            <li class="quick-view"><a href="{{route('singleproduct.index',$single_product->id)}}">+ View Details</a></li>
                            
                        </ul>
                      </div>
                 
                    <div class="pi-text">
                      <div class="catagory-name"><span style="color: {{$colors[rand(0,9)]}}">{{$single_product->subcategory->subcategory_name}}</span> - Size:  {{$single_product->size->name}} </div>
                        <a href="{{route('singleproduct.index',$single_product->id)}}">
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
                </div>
            @endforeach
          </div>
           
          </div>
  
        </div>



        <div class="product-list banner-section no-pad-top">
          <div class="container-fluid">
            <div class="section-title">
              <h2>Hot Product</h2>
            </div>
          <div class="row">
            
              @foreach ($random_product as $single_product)
              @php
              $pd_stock = $single_product->stock($single_product->id);
              @endphp
              <div class="col-lg-5 col-sm-6">
                  <div class="product-item">
                      <div class="pi-pic">
                      <img src="{{asset('public/uploads/products/thumb/'.$single_product->image)}}" alt="{{$single_product->product_name}}">
                      
                        @if($pd_stock < 1)
    
                        <div class="stock-out">Stock Out</div>
                        @elseif($pd_stock > 50)
                          <div class="sale"> <span style="font-size: 11px;font-weight: bold;" class="badge badge-warning">  50+ </span>  Available</div>
                        @else
                        <div class="sale">Only <span style="font-size: 11px;font-weight: bold;" class="badge badge-danger"> {{$pd_stock}}</span> Available </div>
                        @endif
                          <div class="icon">
                            <span class="badge badge-pink">{{$single_product->brand->brand_name}}</span> 
                        </div>
                     
              
                          <ul>
    
                         
                          <li class="w-icon active"><a id="pd-{{$single_product->id}}" data-stock="{{$single_product->stock($single_product->id)}}" data-name="{{$single_product->product_name}}" data-image="{{asset('public/uploads/products/tiny/'.$single_product->image)}}" data-id="{{$single_product->id}}" data-price="@if($single_product->discount_price == NULL) {{ $single_product->price}} @else {{ $single_product->discount_price}} @endif" href="#" class="add-to-cart"><i class=" icon_cart_alt"></i></a></li>
                   
    
    
    
                              <li class="quick-view"><a href="{{route('singleproduct.index',$single_product->id)}}">+ View Details</a></li>
                              
                          </ul>
                        </div>
                   
                      <div class="pi-text">
                        <div class="catagory-name"><span style="color: {{$colors[rand(0,9)]}}">{{$single_product->subcategory->subcategory_name}}</span> - Size:  {{$single_product->size->name}} </div>
                          <a href="{{route('singleproduct.index',$single_product->id)}}">
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
                  </div>
              @endforeach
            </div>
             
            </div>
    
          </div>
  


  </div>
</div>









    <!-- Women Banner Section Begin -->
    <section class="women-banner spad">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                <div class="product-large set-bg" data-setbg="{{asset('public/uploads/ad/cropped/'.$ad->image)}}">
                <h2>{{$ad->title}}</h2>
                <a href="{{$ad->button_link}}">{{$ad->button_text}}</a>
                    </div>
                </div>
                <div class="col-lg-8 offset-lg-1">
                    <div class="filter-control-2">
                        <ul>
                          <li class="product-item active" data-owl-filter="*">All</li>
                            @foreach($category as $item)
                            <li class="product-item" data-owl-filter=".{{Str::slug($item->category_name, '-')}}">{{$item->category_name}}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="product-slider owl-carousel">

                        @foreach ($products as $single_product)
                        @php $stock = $single_product->stock($single_product->id) @endphp
                    <div class="product-item  {{Str::slug($single_product->category->category_name, '-')}}">
                            <div class="pi-pic">
                            <img src="{{asset('public/uploads/products/thumb/'.$single_product->image)}}" alt="{{$single_product->product_name}}">
                              @if($stock < 1)

                                <div style="background: #EA2027" class="sale pp-sale">Stock Out</div>
                                @elseif($stock > 50)
                                  <div class="sale"> <span style="font-size: 11px;font-weight: bold;" class="badge badge-warning">  50+ </span>  Available</div>
                                @else
                                <div class="sale">Only <span style="font-size: 11px;font-weight: bold;" class="badge badge-danger"> {{$stock}}</span> Available </div>
                                @endif
          
                                <ul>
                                    <li class="quick-view"><a href="{{route('singleproduct.index',$single_product->id)}}">+ View Details</a></li>
                                </ul>
                            </div>
                            <div class="pi-text">
                              <div class="catagory-name"><span style="color: {{$colors[rand(0,9)]}}">{{$single_product->subcategory->subcategory_name}}</span> - Size:  {{$single_product->size->name}} </div>
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

    
   
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Women Banner Section End -->


  

      <!-- Banner Section Begin -->
      <div class="banner-section spad">
        <div class="container-fluid">
          <div class="section-title">
            <h2>Product Collection</h2>
          </div>
            <div class="row">
                @foreach ($collections as $cat)
                
                <div class="col-lg-4">
                <a href="{{route('collection.view',$cat->id)}}">
                    <div class="single-banner">
                    <img src="{{asset('public/uploads/category/frontend/'.$cat->image)}}" alt="">
                        <div class="inner-text">
                            <h4>{{$cat->category_name}}</h4>
                        </div>
                    </div>
                  </a>
                </div>
              
                @endforeach
            </div>
        </div>
    </div>
    <!-- Banner Section End -->


  
@endsection

@push('js')
<script src="{{asset('public/assets/frontend/js/owl-2-filter.js')}}"></script>
<script>


    /*------------------
        Product Slider
    --------------------*/
    var owl = $(".product-slider").owlCarousel({
        loop: false,
        margin: 25,
        nav: true,
        items: 4,
        dots: true,
        navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
        smartSpeed: 1200,
        autoHeight: false,
        autoplay: true,
        responsive: {
            0: {
                items: 1,
            },
            576: {
                items: 2,
            },
            992: {
                items: 2,
            },
            1200: {
                items: 4,
            }
        }
    });



    /*---------------------
    Filter Owl
    -----------------------*/


$('.filter-control-2' ).on( 'click', '.product-item', function() {

var $item = $(this);
var filter = $item.data( 'owl-filter' )

owl.owlcarousel2_filter( filter );
$item.addClass( 'active' ).siblings().removeClass( 'active' );

} );


    var timerdate;
    $( document ).ready(function() {


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

    
    @if($deal != null)
    
    // Use this for real timer date
    var timerdate = "{{$deal->expired_at}}";

    @else

    var timerdate = "2020-06-05";
    
    @endif
    console.log(timerdate);

          /*------------------
        CountDown
    --------------------*/

	$("#countdown").countdown(timerdate, function(event) {
        $(this).html(event.strftime("<div class='cd-item'><span>%D</span> <p>Days</p> </div>" + "<div class='cd-item'><span>%H</span> <p>Hrs</p> </div>" + "<div class='cd-item'><span>%M</span> <p>Mins</p> </div>" + "<div class='cd-item'><span>%S</span> <p>Secs</p> </div>"));
    });

  });

 // ************************************************
  // Shopping Cart API
  // ************************************************

  function displayCart() {
      var cartArray = shoppingCart.listCart();
      var output = "";
      for(var i in cartArray) {
        output += "<tr>"
          +"<td class='si-pic'><img src='"+cartArray[i].image +"' alt=''></td>"
          + "<td class='si-text'><div class='product-selected'><p>"+cartArray[i].price+"x " + cartArray[i].count + " = "+Math.round(cartArray[i].total)+"</p><h6>"+cartArray[i].o_name +"</h6>" 
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
      $('.select-button').html('<a href="shop/cart" class="primary-btn view-card">VIEW CART</a><a href="shop/cart/checkout" class="primary-btn checkout-btn">CHECK OUT</a>');
      }else{
        $('.select-button a').attr('disabled', true);
        $('.select-button').hide();
        $('.select-total').html('No Products On the Cart');
  
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
        localStorage.setItem('shoppingCart', JSON.stringify(cart));
      }

    function loadCart() {
      cart = JSON.parse(localStorage.getItem('shoppingCart'));
    }
    if (localStorage.getItem("shoppingCart") != null) {
      loadCart();
    }
      


      
    
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
              title: 'Successfully Added To cart'
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