@extends('layouts.frontendlayout')
@section('title','Edit Shoppingcart')
@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                <a href="{{route('homepage.index')}}"><i class="fa fa-home"></i> Home</a>
                    <a href="{{route('shoppage.index')}}">Shop</a>
                    <span>Cart</span>
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
            <div class="col-lg-12">
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
</section>
<!-- Shopping Cart Section End -->
<!-- Product Shop Section End -->

@endsection

@push('js')
<script>

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
              + "<td class='qua-col first-row'><div class='quantity'><button id='minus-"+ cartArray[i].name +"' class='minus-item input-group-addon btn btn-info' data-name=" + cartArray[i].name + ">-</button>"+"<input type='number' class='item-count cart_qty_input' data-name='" + cartArray[i].name + "' value='" + cartArray[i].count + "' readonly><button id='plus-"+ cartArray[i].name +"' class='plus-item btn btn-info input-group-addon' data-name=" + cartArray[i].name + ">+</button></div></td>"
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
            $('#cart-footer').html('<div class="row"><div class="col-lg-4"><div class="cart-buttons"><a href="'+ShopPageLink+'" class="primary-btn continue-shop">Continue shopping</a></div><div class="discount-coupon"><h6>Discount Codes</h6><form action="#" class="coupon-form"><input type="text" placeholder="Enter your codes" disabled><button type="submit" class="site-btn coupon-btn">Apply</button> </form></div></div><div class="col-lg-4 offset-lg-4"><div class="proceed-checkout"> <ul><li class="subtotal">SUBTOTAL <span>'+Math.round(subTotal)+'</span></li><li class="subtotal">DISCOUNT ('+discountPercentage+'%) <span> - '+Math.round(discountAmount)+'</span></li><li class="subtotal">TAXABLE AMOUNT<span> '+Math.round(netAmount)+'</span></li><li class="subtotal">SHIPPING: <span>+ '+shippingCharge+' Tk</span></li><li class="subtotal">VAT & TAX ('+parseFloat(vatPercentage)+parseFloat(taxPercentage)+'%) <span> + '+Math.round(vat_and_taxAmount)+'</span></li><li class="cart-total">Total<span>Tk.'+Math.round(grandTotal)+'</span></li></ul><a href="javascript:void(0);" class="proceed-btn">CONFIRM & CHECK OUT</a></div></div></div>');
            }else{
              $('#cart-footer').html('<div class="row"><div class="col-lg-4"><div class="cart-buttons"><a href="'+ShopPageLink+'" class="primary-btn continue-shop">Continue shopping</a></div><div class="discount-coupon"><h6>Discount Codes</h6><form action="#" class="coupon-form"><input type="text" placeholder="Enter your codes" disabled><button type="submit" class="site-btn coupon-btn">Apply</button> </form></div></div><div class="col-lg-4 offset-lg-4"><div class="proceed-checkout"> <ul><li class="subtotal">SUBTOTAL <span>'+Math.round(subTotal)+'</span></li><li class="subtotal">DISCOUNT ('+discountPercentage+'%) <span> - '+Math.round(discountAmount)+'</span></li><li class="subtotal">NET AMOUNT<span> '+Math.round(netAmount)+'</span></li><li class="subtotal">SHIPPING: <span>+ '+shippingCharge+' Tk</span></li><li class="cart-total">Total<span>Tk.'+Math.round(grandTotal)+'</span></li></ul><a href="'+checkoutPageLink+'" class="proceed-btn">CONFIRM & CHECK OUT</a></div></div></div>');
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
