@extends('layouts.frontendlayout')
@section('title','Shop')
@section('content')
@php
$colors = ["#eb4d4b", "#A3CB38", "#f1c40f", "#f39c12", "#2980b9", "#ff7979", "purple","#badc58","#78e08f","#079992"];    


@endphp
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{route('homepage.index')}}"><i class="fa fa-home"></i> Home</a>
                    <span>Shop</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<!-- Product Shop Section Begin -->
<section class="product-shop spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1 produts-sidebar-filter">
                <div class="filter-widget">
                    <h4 class="fw-title">Collection</h4>
                    <ul class="filter-catagories">
                        @foreach ($categories as $category)
                    <li><a href="{{route('shoppage.category',$category->id)}}">{{$category->category_name}}</a></li>
                        @endforeach

                    </ul>
                </div>
              <form action="{{route('shoppage.filterbyprice')}}" method="POST">
                @csrf
                <div class="filter-widget">
                    <h4 class="fw-title">Brand</h4>
                    <div class="fw-brand-check">
                      @foreach ($brands as $brand)
                      <div class="bc-item">
                        <label for="bc-{{$brand->id}}">
                          {{$brand->brand_name}}
                            <input type="checkbox" id="bc-{{$brand->id}}" name="brand[]" value="{{$brand->id}}">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                      @endforeach
                        
     
                    </div>
                </div>
                <div class="filter-widget">
                    <h4 class="fw-title">Price</h4>
                    <div class="filter-range-wrap">
                        <div class="range-slider">
                            <div class="price-input">
                            <input type="text" id="minamount" name="minamount">
                            <input type="text" id="maxamount" name="maxamount">
                            </div>
                        </div>
                        <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                            data-min="{{round($minprice)}}" data-max="{{round($maxprice)}}">
                            <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                            <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                            <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                        </div>
                    </div>
                    <button type="submit" class="btn filter-btn">Filter</button>
                </div>
              </form>

                <div class="filter-widget">
                    <h4 class="fw-title">Tags</h4>
                    <div class="fw-tags">
                      @foreach ($tags as $tag)
                    <a href="{{route('shoppage.tagproduct',$tag->id)}}">{{$tag->tag_name}}</a>
                      @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-9 order-1 order-lg-2">

              <div class="row justify-content-center">
              <div class="col-lg-8">
               
                    <div class="single-banner">
                      <img style="border-radius: 5px;" src="{{asset('public/uploads/category/frontend/'.$cat_info->image)}}" alt="category banner">
                        <div class="inner-text">
                            <h4> " {{$cat_info->category_name}} " Collection</h4>
                        </div>
                    </div>

                </div>
              </div>


            
                @if(count($products) > 0)
                <div class="product-list">

                    <div class="row">
                       
                        @foreach ($products as $single_product)
                        @php
                        $pd_stock = $single_product->stock($single_product->id);
                        @endphp
                        <div class="col-lg-4 col-sm-6">
                            <div class="product-item">
                                <div class="pi-pic">
                                    <img src="{{asset('public/uploads/products/original/'.$single_product->image)}}" alt="">
                                
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

                                      @if($pd_stock > 1)
            
                                    <li class="w-icon active"><a id="pd-{{$single_product->id}}" data-stock="{{$pd_stock}}" data-name="{{$single_product->product_name}}" data-image="{{asset('public/uploads/products/tiny/'.$single_product->image)}}" data-id="{{$single_product->id}}" data-price="{{$single_product->current_price}}" href="#" class="add-to-cart"><i class=" icon_cart_alt"></i></a></li>
                                    @endif
                                        <li class="quick-view"><a href="{{route('singleproduct.index',$single_product->id)}}">+View Details</a></li>
                                        
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
                    @else 


                        <h4 class="text-center">No Product Found</h4>
                        @endif
                   
                        
                </div>


                <div class="row justify-content-center pd-pagination">
                  {{$products->onEachSide(1)->links()}}
                </div>
                
            </div>
        </div>
    </div>
</section>
<!-- Product Shop Section End -->

@endsection

@push('js')
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