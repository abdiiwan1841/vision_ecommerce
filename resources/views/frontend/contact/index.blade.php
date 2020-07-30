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