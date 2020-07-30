
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

<!-- Product Shop Section Begin -->
 <!-- Shopping Cart Section Begin -->
 <section class="shopping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                
                
            @if(Auth::check())
            <form action="{{route('checkoutpage.oldcustomerorder',Auth::user()->id)}}" method="POST">
                @csrf
                
                <h4 class="mb-5"><strong>  {{Auth::user()->name }} </strong> <br> Please  Review Your Biiling Details</h4>
                
                <div class="row">
                    <div class="col-lg-6">

  
                                    
                        <div class="form-group @error('division') has-error @enderror">
                            <label for="division">Division<span>*</span></label>
                            <select name="division" id="division" class="form-control">
                                <option value="">Select Division</option>
                                @foreach ($divisions as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                
                            </select>
                            @error('division')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group @error('district') has-error @enderror">
                            <label for="district">District<span>*</span></label>
                            <select name="district" id="district" class="form-control">
                                <option value="">Select District</option>
                            
                                
                            </select>
                            @error('district')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
                
                        <div class="form-group @error('area') has-error @enderror">
                            <label for="area">Area<span>*</span></label>
                            <select name="area" id="area" placeholder="Select a Area" class="form-control" id="area">
                                <option value="">Select Area</option>
                            </select>
                            @error('area')
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
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="address">Address<span>*</span></label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address"  rows="7" placeholder="Enter Your Addres">@if(old('address')){{old('address')}} @else {{Auth::user()->address}} @endif</textarea>
                            
                            @error('address')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
 
                    </div>
                    <div class="col-lg-6 mt-5">
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
                    <div class="checkout-content">
                        <a href="{{route('login')}}" class="site-btn place-btn">Click Here To Login</a>
                    </div>
                    <h4>Biiling Details</h4>
                <div class="row">
                    <div class="col-lg-6">
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
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                
                            </select>
                            @error('division')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>


                        <div class="form-group @error('district') has-error @enderror">
                            <label for="district">District<span>*</span></label>
                            <select name="district" id="district" class="form-control">
                                <option value="">Select District</option>
                            
                                
                            </select>
                            @error('district')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
                
                        <div class="form-group @error('area') has-error @enderror">
                            <label for="area">Area<span>*</span></label>
                            <select name="area" id="area" placeholder="Select a Area" class="form-control" id="area">
                                <option value="">Select Area</option>
                            </select>
                            @error('area')
                            <small class="form-error">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="address">Address<span>*</span></label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address"  rows="7" placeholder="Enter Your Addres">{{old('address')}}</textarea>
                            
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
                    </div>


                    <div class="col-lg-6 mt-5">
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
            @endif


            </div>
            <div class="col-lg-5">           
                <div class="cart-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th class="p-name">Product Name</th>
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
                <div id="cart-footer">
                   
                </div>

      </div>
              
              
                
        
            </div>
        </div>
    </div>
</section>
<!-- Shopping Cart Section End -->
<!-- Product Shop Section End -->

@endsection
@push('css')
<!-- Select 2 min  Css -->
<link href="{{asset('public/assets/css/select2.min.css')}}" rel="stylesheet" />
<!-- Select 2 Bootstrap  Css -->
<link href="{{asset('public/assets/css/select2-bootstrap.min.css')}}" rel="stylesheet" />
@endpush
@push('js')
<!-- Select 2 js -->
<script src="{{asset('public/assets/js/select2.min.js')}}"></script>


<script>
    $('#division').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a Division",
});
$('#district').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a District",
});

$('#area').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a Area",
});

    var base_url = '{{url('/')}}';
    var output = '';



    $("#division").change(function(){
        var division_id = $("#division").val();
        if(division_id.length > 0){
          $.get("{{asset('')}}api/district/"+division_id, function(data, status){
            if(data.length>0){
            output = '';
            $(data).each(function(index,element){
               output += '<option value="'+element.id+'">'+element.name+'</option>';
            });
               $("#district").html('<option value="0">--Choose Your District--</option>'+output);
               $("#area").html('');
 
            }else{
                $("#district").html('<option value="">No District Found</option>');
            }
        });
        }else{
          $("#area").html('<option value="">No District Found</option>');
        }
        });




       
        $("#district").change(function(){
         district_id = $("#district").val();
        if(district_id != null){
          $.get("{{asset('')}}api/area/"+district_id, function(data, status){
            if(data.length>0){
            output = '';
            $(data).each(function(index,element){
               output += '<option value="'+element.id+'">'+element.name+'</option>';
            });
               $("#area").html(output);
            }else{
                $("#area").html('<option value="">No Area Found</option>');
            }
        });
        }else{
          $("#area").html('<option value="">No Area Found</option>');
        }
        });


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
              +"<td class='cart-pic first-row'><img src='"+cartArray[i].image +"' alt=''></td>"
              +"<td class='cart-title first-row'><h5>"+cartArray[i].o_name +"</h5></td>"
              + "<td class='p-price first-row'>"+cartArray[i].price+"</td>"
              + "<td class='qua-col first-row'><div class='quantity'><button id='minus-"+ cartArray[i].name +"' class='minus-item input-group-addon btn btn-dark' data-name=" + cartArray[i].name + ">-</button>"+"<input type='number' class='item-count cart_qty_input' data-name='" + cartArray[i].name + "' value='" + cartArray[i].count + "' readonly><button id='plus-"+ cartArray[i].name +"' class='plus-item btn btn-dark input-group-addon' data-name=" + cartArray[i].name + ">+</button></div></td>"
              + "<td class='total-price first-row'>"+Math.round(cartArray[i].total)+"</td>"
              + "<td class='si-close first-row'><button class='delete-item btn' data-name=" + cartArray[i].name + "><i class='ti-close'></i></button></td>"
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
            $('#cart-footer').html('<div class="row"><div class="col-lg-12"><div class="proceed-checkout"> <ul><li class="subtotal">SUBTOTAL <span>'+Math.round(subTotal)+'</span></li><li class="subtotal">DISCOUNT ('+discountPercentage+'%) <span> - '+Math.round(discountAmount)+'</span></li><li class="subtotal">TAXABLE AMOUNT<span> '+Math.round(netAmount)+'</span></li><li class="subtotal">SHIPPING: <span>+ '+shippingCharge+' Tk</span></li><li class="subtotal">VAT & TAX ('+parseFloat(vatPercentage)+parseFloat(taxPercentage)+'%) <span> + '+Math.round(vat_and_taxAmount)+'</span></li><li class="cart-total">Total<span>Tk.'+Math.round(grandTotal)+'</span></li></ul><a href="javascript:void(0);" class="proceed-btn">CONFIRM & CHECK OUT</a></div></div></div>');
            }else{
              $('#cart-footer').html('<div class="row"><div class="col-lg-12"><div class="proceed-checkout"> <ul><li class="subtotal">SUBTOTAL <span>'+Math.round(subTotal)+'</span></li><li class="subtotal">DISCOUNT ('+discountPercentage+'%) <span> - '+Math.round(discountAmount)+'</span></li><li class="subtotal">NET AMOUNT<span> '+Math.round(netAmount)+'</span></li><li class="subtotal">SHIPPING: <span>+ '+shippingCharge+' Tk</span></li><li class="cart-total">Total<span>Tk.'+Math.round(grandTotal)+'</span></li></ul></div></div></div>');
            }

  
         
          }else{
            $('#cart-footer').hide();
      
          }

        }else{
            $('.cart-table').text('');
            $('.cart-table').html('<h3 class="text-center">No Product Found On the Cart</h3>');
            $('#cart-footer').hide();
        }
      
        }
        



        
</script>

<script src="{{asset('public/assets/frontend/js/cart.js')}}"></script>

@endpush
