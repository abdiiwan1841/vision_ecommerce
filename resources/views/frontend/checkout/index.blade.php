
@extends('layouts.frontendlayout')
@section('title','Checkout')
@section('content')
    
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{route('homepage.index')}}"><i class="fa fa-home"></i> Home</a>
                    <a href="{{route('shoppage.index')}}">Shop</a>
                    <a href="{{route('cartpage.index')}}">Cart</a>
                    <span>Checkout</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->
@if(Session::has('err'))
    {{Session::get('err')}}
@endif
<!-- Product Shop Section Begin -->
@if (!Auth::check())
<div class="container">
    <div class="row">
        <div class="mt-5">
            <a href="{{route('login')}}" class="btn btn-success btn-sm ml-3"><i class="fa fa-sign-in"></i>    Click Here To Login</a>
        </div>
    </div>
</div>
@endif

 <!-- Shopping Cart Section Begin -->
 <section class="shopping-cart">
    <div class="container">
        <div class="row">
            <div class="col-lg-8" style="padding: 10px;display: block">           
                <h4 class="mb-5"><strong>CART </strong> DETAILS</h4>
                    <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Image</th>
                                <th class="p-name">Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="show-cart">
                          
                        </tbody>
                    </table>
                </div>
        
                <div class="mb-3" id="cart-footer">
                   
                </div>

      </div>
            <div class="col-lg-4">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                
            @if(Auth::check())
            <form action="{{route('checkoutpage.oldcustomerorder',Auth::user()->id)}}" method="POST">
                @csrf
                
                <h4 class="mb-5"><strong>  {{Auth::user()->name }} </strong> <br> Please  Review Your Biiling Details</h4>
                
                <div class="row">
                    <div class="col-lg-12">

                        <input type="hidden" id="cartdata" name="cartdata">
                                    
                        <div class="form-group">
                            <label for="division">Division<span>*</span></label>
                            <select name="division" id="division" class="form-control @error('division') is-invalid @enderror">
                                <option value="">Select Division</option>
                                @foreach ($divisions as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                
                            </select>
                            @error('division')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>



            
                        <div class="form-group">
                            <label for="phone">Phone<span>*</span></label>
                        <input type="text" id="phone" placeholder="Enter Your phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="@if(old('phone')){{old('phone')}} @else {{Auth::user()->phone}} @endif">
                            @error('phone')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
             

                        <div class="form-group">
                            <label for="address">Address<span>*</span></label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address"  rows="4" placeholder="Enter Your Addres">@if(old('address')){{old('address')}} @else {{Auth::user()->address}} @endif</textarea>
                            
                            @error('address')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control @error('payment_method')is-invalid @enderror">
                                <option value="">-Select Payment Method-</option>
                                @foreach ($payment_methods as $pm)
                                   <option value="{{$pm->id}}">{{$pm->name}}</option>
                                @endforeach

                            </select>
                            @error('payment_method')
                            <small class="form-error">{{ $message }}</small>
                            @enderror

                            <div id="payment_method_info">
                                
                            </div>
                        </div>

                    </div>
    
                    <div class="col-lg-12 order-btn mt-5">
                        <button type="submit" class="site-btn place-btn">Place Order</button>
                    </div>
        
        
                </div>



            </form>
                @else 

                <form action="{{route('checkoutpage.store')}}" method="POST">
                    @csrf
                   
                    <h4 class="mb-5"><strong>BILLING</strong> DETAILS</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" id="cartdata" name="cartdata">
                        <div class="form-group">
                            <label for="name">Name<span>*</span></label>
                        <input type="text" id="name" placeholder="Enter Your Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}">

                            @error('name')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
       
                        <div class="form-group">
                            <label for="email">Email<span>*</span></label>
                            <input type="text" id="email" placeholder="Enter Your Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email')}}">
                            @error('email')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone<span>*</span></label>
                        <input type="text" id="phone" placeholder="Enter Your phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{old('phone')}}">
                            @error('phone')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
  
                                   
                        <div class="form-group @error('division') has-error @enderror">
                            <label for="division">Division<span>*</span></label>
                            <select name="division" id="division" class="form-control">
                                <option value="">Select Division</option>
                                @foreach ($divisions as $item)
                                @if (old('division') == $item->id)
                                <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                @else
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endif
                                @endforeach
                                
                            </select>
                            @error('division')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>



                
     
               
   
                        <div class="form-group">
                            <label for="address">Address<span>*</span></label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address"  rows="4" placeholder="Enter Your Addres">{{old('address')}}</textarea>
                            
                            @error('address')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                          <label for="password">password<span>*</span></label>
                          <input type="password" id="password" placeholder="Enter Password" class="form-control @error('password') is-invalid @enderror" name="password">
                          @error('password')
                          <small class="form-error">{{ $message }}</small>
                          @enderror
                      </div>
                      <div class="form-group">
                        <label for="password-confirm">Confirm Password<span>*</span></label>
                        <input type="password" id="password-confirm" placeholder="Confirm your Password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation">

                        @error('password_confirmation')
                        <small class="form-error">{{ $message }}</small>
                        @enderror
                       
                    </div>
          


      
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control @error('payment_method')is-invalid @enderror">
                                <option value="">-Select Payment Method-</option>
                                @foreach ($payment_methods as $pm)
                                @if (old('payment_method') == $pm->id)
                                    <option value="{{$pm->id}}" selected>{{$pm->name}}</option>
                                @else
                                    <option value="{{$pm->id}}">{{$pm->name}}</option>
                                @endif
                                @endforeach

                            </select>
                            @error('payment_method')
                            <small class="form-error">{{ $message }}</small>
                            @enderror

                            <div id="payment_method_info">
                                
                            </div>
                        </div>

                    </div>

                    
                    <div class="col-lg-12 order-btn mt-5">
                        <button type="submit" class="site-btn place-btn btn-block">Place Order</button>
                    </div>
        
                </div>

                
            </form>
            @endif


            </div>

              
              
                
        
            </div>
        </div>
    </div>
</section>
<!-- Shopping Cart Section End -->


@endsection

@push('js')

<script src="{{asset('public/assets/js/axios.min.js')}}"></script>

<script>
    var HomePageLink = '{{route('homepage.index')}}';
    $("#cartdata").val(JSON.stringify(localStorage.shoppingCart));



    var base_url = '{{url('/')}}';
    var output = '';


        $("#payment_method").change(function(){
        var pm_id = $("#payment_method").val();
        if(pm_id.length > 0){
          $.get("{{asset('')}}api/paymentmethod/"+pm_id, function(data, status){
              if(data.ac_number === null){
                $("#payment_method_info").html('<div class="payment_info"><img class="img-thumbnail img-responsive" src="'+base_url+'/public/uploads/paymentmethod/original/'+data.image+'" ><table class="table table-hover mt-5"><tr><th scope="col">Instructions: </th></tr></table> <hr><p>'+data.description+'</p></div>');
              }else{
                $("#payment_method_info").html('<div class="payment_info"> <img class="img-thumbnail img-responsive" src="'+base_url+'/public/uploads/paymentmethod/original/'+data.image+'" ><div class="form-group"> <label for="txn_id">TXN ID</label><input type="text" class="form-control" name="txn_id" placeholder="Enter Txn ID" id="txn_id" ></div><p>'+data.description+'</p><table class="table table-hover mt-5"> <tr><th scope="col">A/C Number</th> <th>'+data.ac_number+'</th></tr></table>  <hr> </div>');
            }
            
            
        });
        }
        });





var ShopPageLink = '{{route('shoppage.index')}}';
var checkoutPageLink = '{{route('checkoutpage.index')}}';
var vatPercentage = '{{$charges->vat}}';
var taxPercentage = '{{$charges->tax}}';
var discountPercentage = '{{$charges->discount}}';
var shippingCharge = '{{$charges->shipping}}';


  function displayCart() {
       
          var cartArray = shoppingCart.listCart();
          if(cartArray.length > 0){
          var output = "";
          for(var i in cartArray) {
            output += "<tr>"
              +"<td class='cart-pic'><img style='border-radius: 100%' src='"+cartArray[i].image +"' alt=''></td>"
              +"<td class='cart-title '><h5>"+cartArray[i].o_name +"</h5></td>"
              + "<td class='p-price'>"+cartArray[i].price+"</td>"
              + "<td style='width: 98px;display:block' class='qua-col'><div class='quantity'><button id='minus-"+ cartArray[i].name +"' class='minus-item input-group-addon btn btn-dark btn-sm' data-name=" + cartArray[i].name + ">-</button>"+"<input type='text' style='width: 30px' class='item-count cart_qty_input' data-name='" + cartArray[i].name + "' value='" + cartArray[i].count + "' readonly><button id='plus-"+ cartArray[i].name +"' class='plus-item btn-sm btn btn-dark input-group-addon' data-name=" + cartArray[i].name + ">+</button></div></td>"
              + "<td class='total-price'>"+Math.round(cartArray[i].total)+"</td>"
              + "<td class='si-close'><button class='delete-item btn-danger btn-sm  btn' data-name=" + cartArray[i].name + "><i class='fa fa-times'></i></button></td>"
              +  "</tr>";
          }
          $('.show-cart').html(output);
          $('.cart-price').html(Math.round(shoppingCart.totalCart()) + 'Tk');
          $('.total-count').html(Math.round(shoppingCart.totalCount()));
          if(cart.length >0){
          $('#cart-footer').show();

            var subTotal = shoppingCart.totalCart();
            var discountAmount = (shoppingCart.totalCart())*(discountPercentage/100);
            var netAmount = subTotal-discountAmount;
            var vatAmount = netAmount*(vatPercentage/100);
            var taxAmount = netAmount*(taxPercentage/100);
            var vat_and_taxAmount = vatAmount+taxAmount;

            var grandTotal = ( parseFloat(netAmount) + parseFloat(vat_and_taxAmount) + parseFloat(shippingCharge) );


            if(vat_and_taxAmount > 0){
            $('#cart-footer').html('<div class="row"><div class="col-lg-12"><div class="proceed-checkout"> <ul><li class="subtotal">SUBTOTAL <span>'+Math.round(subTotal)+'</span></li><li class="subtotal">DISCOUNT ('+Math.round(discountPercentage)+'%) <span> - '+Math.round(discountAmount)+'</span></li><li class="subtotal">TAXABLE AMOUNT<span> '+Math.round(netAmount)+'</span></li><li class="subtotal">SHIPPING: <span>+ '+shippingCharge+' Tk</span></li><li class="subtotal">VAT & TAX ('+parseFloat(vatPercentage)+parseFloat(taxPercentage)+'%) <span> + '+Math.round(vat_and_taxAmount)+'</span></li><li class="cart-total">Total<span>Tk.'+Math.round(grandTotal)+'</span></li></ul><a href="javascript:void(0);" class="proceed-btn">CONFIRM & CHECK OUT</a></div></div></div>');
            }else{
              $('#cart-footer').html('<div class="row"><div class="col-lg-12"><div class="proceed-checkout"> <ul><li class="subtotal">SUBTOTAL <span>'+Math.round(subTotal)+'</span></li><li class="subtotal">DISCOUNT ('+Math.round(discountPercentage)+'%) <span> - '+Math.round(discountAmount)+'</span></li><li class="subtotal">NET AMOUNT<span> '+Math.round(netAmount)+'</span></li><li class="subtotal">SHIPPING: <span>+ '+shippingCharge+' Tk</span></li><li class="cart-total">Total<span>Tk.'+Math.round(grandTotal)+'</span></li></ul></div></div></div>');
            }

  
         
          }else{
            $('#cart-footer').hide();
      
          }

        }else{
            $('section.shopping-cart').html('<div style="border: 1px solid #ddd;border-radius: 5px;padding: 20px"><h3 class="text-center">Currently there is no product on the cart <span id="time"></span></h3><br><h3 class="text-center">You will redicted to homepage in<span id="time"></span></h3><br><h1 class="num" style="font-size: 50px;text-align:center;font-weight: bold;margin: 0px;"></h1></div>');



    var num = 4;
    setInterval(function () {
        if(num > 0){
          num--;
        }
        $(".num").text(num+' seconds');
        if(num==1){
            window.location.href = HomePageLink; 
            return;
        }
     }, 1000)
     
     }
      
        }
        



        
</script>

<script src="{{asset('public/assets/frontend/js/checkoutpage-cart.js')}}"></script>

@endpush
