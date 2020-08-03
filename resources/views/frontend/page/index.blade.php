@extends('layouts.frontendlayout')
@section('title',$page->page_title)
@section('content')
<section class="hero bg-cover bg-position py-4" style="background: url({{asset('public/uploads/page/'.$page->banner_image)}})">
<div class="container py-5 index-forward text-white">
    <h1 class="page-title">{{$page->page_title}}</h1>
    <!-- Breadcrumb-->
    <nav aria-label="breadcrumb" class="page-breadcrumb">
    <ol class="breadcrumb bg-none mb-0 p-0">
    <li class="breadcrumb-item pl-0"><a href="{{route('homepage.index')}}"> <i class="fa fa-home mr-2"></i>Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{$page->page_title}}</li>
    </ol>
    </nav>
</div>
</section>
<div class="container">
    <div class="row">
        <div class="col-lg-12">

        <div style="font-size: 20px;margin-top: 40px;">
        {!!$page->page_description!!}
    </div>
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
@endpush