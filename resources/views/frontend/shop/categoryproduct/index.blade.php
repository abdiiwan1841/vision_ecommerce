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
          @include('component/frontend/shop-sidebar')
            <div class="col-lg-9 order-1 order-lg-2">
                
                @if(count($products) > 0)
                <div class="product-list">
                  @if(Request::is('shop/category*'))
                  <h5 class="text-center mb-5"> <strong>Filter By Category: "{{$cat_info->category_name}}"</strong> </h5>
                  @elseif(Request::is('shop/tag*'))
                  <h5 class="text-center mb-5"> <strong>Filter By Tag: "{{$tag_info->tag_name}}"</strong> </h5>
                  @elseif(Request::is('shop/type*'))
                  <h5 class="text-center mb-5"> <strong>Filter By Type: "{{$subcat_info->subcategory_name}}"</strong> </h5>
                 

                  @endif
                    <div class="row">
                       
                        @foreach ($products as $single_product)

                        <div class="col-lg-4 col-sm-6">
                            <div class="product-item">
                                <div class="pi-pic">
                                    <img src="{{asset('public/uploads/products/original/'.$single_product->image)}}" alt="">
                                
                                   
                                  @if($single_product->in_stock == 0)
                                  <div class="stock-out">Stock Out</div>
                                  @endif
                                    
                                    <div class="icon">
                                        <span class="badge badge-pink">{{$single_product->brand->brand_name}}</span> 
                                    </div>
                                    <ul>

          
            
                                    <li class="w-icon active"><a id="pd-{{$single_product->id}}" data-instock="{{$single_product->in_stock}}" data-name="{{$single_product->product_name}}" data-image="{{asset('public/uploads/products/tiny/'.$single_product->image)}}" data-id="{{$single_product->id}}" data-price="{{$single_product->current_price}}" href="#" class="add-to-cart"><i class=" icon_cart_alt"></i></a></li>

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
  