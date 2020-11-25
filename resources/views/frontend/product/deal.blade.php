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
               
              <a id="pd-{{$single_product->id}}" data-instock="{{$single_product->in_stock}}" data-name="{{$single_product->product_name}}" data-image="{{asset('public/uploads/products/tiny/'.$single_product->image)}}" data-id="{{$single_product->id}}" data-price="{{$deal->amount}}" href="#" class="add-to-cart btn btn-cart"><i class=" icon_cart_alt"></i> Add To Cart</a>
                
               
              </div>

            </div>
            
          </div>

        </div>
        <div class="card-body">
          <p>{!!$single_product->description!!}</p>
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
            + "<td class='si-text'><div class='product-selected'><p>"+cartArray[i].price+" x " + cartArray[i].count + "="+Math.round(cartArray[i].total)+"</p><h6>"+cartArray[i].o_name +"</h6>" 
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