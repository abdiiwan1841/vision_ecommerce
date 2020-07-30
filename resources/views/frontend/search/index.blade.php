@extends('layouts.frontendlayout')
@section('title','Search Result')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{route('homepage.index')}}"><i class="fa fa-home"></i> Home</a>
                    <span>search</span>
                </div>
                <h3 class="text-center mt-5">{{$products->count()}} Results Found</h3>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->
@if($products->count() > 0)
<!-- Product Shop Section Begin -->
<section class="product-shop spad">
    <div class="container">
        <div class="row">
           
            <div class="col-lg-12 order-1 order-lg-2">
                <div class="product-list">
                    <div class="row">
                      
                        @foreach ($products as $single_product)
                        <div class="col-lg-3 col-sm-6">
                            <div class="product-item">
                                <div class="pi-pic">
                                    <img src="{{asset('public/uploads/products/thumb/'.$single_product->image)}}" alt="">
                                
                                    @if($single_product->in_stock == 0)
                                    <div class="stock-out">Stock Out</div>
                                    @else
                                    <div class="sale">Available</div>
                                    @endif
                                    
                                    <div class="icon">
                                        <i class="icon_heart_alt"></i>
                                    </div>
                                    <ul>
                                    <li class="w-icon active"><a id="pd-{{$single_product->id}}" data-instock="{{$single_product->in_stock}}" data-name="{{$single_product->product_name}}" data-image="{{asset('public/uploads/products/tiny/'.$single_product->image)}}" data-id="{{$single_product->id}}" data-price="@if($single_product->discount_price == NULL) {{ $single_product->price}} @else {{ $single_product->discount_price}} @endif" href="#" class="add-to-cart"><i class=" icon_cart_alt"></i></a></li>
                                        <li class="quick-view"><a href="{{route('singleproduct.index',$single_product->id)}}">+ View</a></li>
                                        
                                    </ul>
                                </div>
                                <div class="pi-text">
                                    <div class="catagory-name">{{$single_product->subcategory->subcategory_name}}</div>
                                    <a href="#">
                                        <h5>{{$single_product->product_name}}</h5>
                                    </a>
                                    <div class="product-price">
                                        Tk.{{$single_product->discount_price}}
                                        <span>Tk.{{$single_product->price}}</span>
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
</section>
<!-- Product Shop Section End -->
@else
<div class="text-center" style="padding: 100px 0">
  <img style="width: 150px" src="{{asset('public/assets/frontend/sad.png')}}" class="img-responsive" alt="">
</div>

@endif
@endsection

@push('js')
<script>
 
    
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



</script>
<script src="{{asset('public/assets/frontend/js/cart.js')}}"></script>
@endpush