@if(Session::has('ShoppingCart'))

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


var vatPercentage = '{{$charges->vat}}';
var taxPercentage = '{{$charges->tax}}';
var discountPercentage = '{{$charges->discount}}';
var shippingCharge = '{{$charges->shipping}}';


function strip(number) {
    return (parseFloat(number).toPrecision(4));
}


//Precise Discount vat etc
function precise(x) {
  return Number.parseFloat(x).toPrecision(3);
}



//Toater Alert 
const Toast = Swal.mixin({
    toast: true,
    position: 'top-start',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
  


  function displayCart() {
          var cartArray = shoppingCart.listCart();
          var output = "";
          for(var i in cartArray) {
            output += "<tr>"
              +"<td class='cart-pic first-row'><img src='"+cartArray[i].image +"' alt=''></td>"
              +"<td class='cart-title first-row'><h5>"+cartArray[i].o_name +"</h5></td>"
              + "<td class='p-price first-row'>"+cartArray[i].price+"</td>"
              + "<td class='qua-col first-row'><div class='quantity'><button id='minus-"+ cartArray[i].name +"' class='minus-item input-group-addon btn btn-info' data-name=" + cartArray[i].name + ">-</button>"+"<input type='number' class='item-count cart_qty_input' data-name='" + cartArray[i].name + "' value='" + cartArray[i].count + "' readonly><button id='plus-"+ cartArray[i].name +"' class='plus-item btn btn-info input-group-addon' data-name=" + cartArray[i].name + ">+</button></div></td>"
              + "<td class='total-price first-row'>"+cartArray[i].total+"</td>"
              + "<td class='si-close first-row'><button class='delete-item btn' data-name=" + cartArray[i].name + "><i class='ti-close'></i></button></td>"
              +  "</tr>";
          }
          $('.show-cart').html(output);
          $('.cart-price').html(strip(shoppingCart.totalCart()) + 'Tk');
          $('.total-count').html(strip(shoppingCart.totalCount()));
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
            $('#cart-footer').html('<div class="row"><div class="col-lg-4"><div class="cart-buttons"><a href="javascript:void(0);" class="primary-btn continue-shop">Continue shopping</a></div><div class="discount-coupon"><h6>Discount Codes</h6><form action="#" class="coupon-form"><input type="text" placeholder="Enter your codes" disabled><button type="submit" class="site-btn coupon-btn">Apply</button> </form></div></div><div class="col-lg-4 offset-lg-4"><div class="proceed-checkout"> <ul><li class="subtotal">SUBTOTAL <span>'+Math.round(subTotal)+'</span></li><li class="subtotal">DISCOUNT ('+discountPercentage+'%) <span> - '+Math.round(discountAmount)+'</span></li><li class="subtotal">TAXABLE AMOUNT<span> '+Math.round(netAmount)+'</span></li><li class="subtotal">SHIPPING: <span>+ '+shippingCharge+' Tk</span></li><li class="subtotal">VAT & TAX ('+parseFloat(vatPercentage)+parseFloat(taxPercentage)+'%) <span> + '+Math.round(vat_and_taxAmount)+'</span></li><li class="cart-total">Total<span>Tk.'+Math.round(grandTotal)+'</span></li></ul><a href="javascript:void(0);" class="proceed-btn">CONFIRM & CHECK OUT</a></div></div></div>');
            }else{
              $('#cart-footer').html('<div class="row"><div class="col-lg-4"><div class="cart-buttons"><a href="javascript:void(0);" class="primary-btn continue-shop">Continue shopping</a></div><div class="discount-coupon"><h6>Discount Codes</h6><form action="#" class="coupon-form"><input type="text" placeholder="Enter your codes" disabled><button type="submit" class="site-btn coupon-btn">Apply</button> </form></div></div><div class="col-lg-4 offset-lg-4"><div class="proceed-checkout"> <ul><li class="subtotal">SUBTOTAL <span>'+Math.round(subTotal)+'</span></li><li class="subtotal">DISCOUNT ('+discountPercentage+'%) <span> - '+Math.round(discountAmount)+'</span></li><li class="subtotal">NET AMOUNT<span> '+Math.round(netAmount)+'</span></li><li class="subtotal">SHIPPING: <span>+ '+shippingCharge+' Tk</span></li><li class="cart-total">Total<span>Tk.'+Math.round(grandTotal)+'</span></li></ul><a href="javascript:void(0);" class="proceed-btn">CONFIRM & CHECK OUT</a></div></div></div>');
            }

  
         
          }else{
            $('#cart-footer').hide();
      
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
          complete: function(data) {
            if(data.status == 200){
              console.log(data);
    
            }
         }
      });
    
      }
      


      $.get('{{route('session.getdata')}}',function(data){
      
        if(data.length > 0){
            cart = data;
            displayCart();
     
        }
               
        
      });
      
    
      // =============================
      // Public methods and propeties
      // =============================
      var obj = {};
      
   
      // Add to cart
      obj.addItemToCart = function(name,o_name, price, count, id,image,stock) {
        for(var item in cart) {
          if(cart[item].name === name) {
            if(cart[item].stock == cart[item].count ){
              Toast.fire({
                icon: 'error',
                title: 'Stock Out'
              });
              return;
            }else{
              cart[item].count ++;
            return;
            }
          }
        }
       var item = new Item(name,o_name,price, count, id,image,stock);
      cart.push(item);
        //saveCart();
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
                title: 'Stock Out'
              });
              return;
        }else{
            $('#pd-'+id).html('<i class="icon_check"></i>').css('background','#44bd32');
            var item = new Item(o_name,name, price, count, id,image,stock);
            cart.push(item);
            saveCart();
            Toast.fire({
              icon: 'success',
              title: 'SuccessFully Added To Cart'
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
                saveCart();
              }
              break;
            }
        }
        //saveCart();
      }
    
      // Remove all items from cart
      obj.removeItemFromCartAll = function(name) {
        for(var item in cart) {
          if(cart[item].name === name) {
            Toast.fire({
              icon: 'success',
              title: cart[item].name+' Removed Successfully'
            });
            $('#pd-'+cart[item].id).html('<i class="icon_bag_alt"></i>');
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

    
            // Save Increment
    function saveIncrement(link) {    
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
          success: function(data) {
          window.location=link;
            
         }
      });
    
      }






 
  
    // *****************************************
    // Triggers / Events
    // ***************************************** 
    // Add item
    $('.add-to-cart').click(function(event) {
      event.preventDefault();
     // $('.cart-hover').show().delay(5000).fadeOut();
  
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
      
      Swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ED4C67',
        cancelButtonColor: '#A3CB38',
        confirmButtonText: 'Yes, Remove it!'
      }).then((result) => {
        if (result.value) {
          var name = $(this).data('name')
        shoppingCart.removeItemFromCartAll(name);
          Swal.fire(
            'Removed!',
            'Your Product Has Been Remove From Cart',
            'success'
          )
          displayCart();
        }
      })
      
    })
    
    
    // -1
    $('.show-cart').on("click", ".minus-item", function(event) {
      var name = $(this).data('name')
      shoppingCart.removeItemFromCart(name);
      displayCart();
      
    })
    // +1
    $('.show-cart').on("click", ".plus-item", function(event) {
      var name = $(this).data('name');
      shoppingCart.addItemToCart(name);
      displayCart();

      
      
    });

    $('#cart-footer').on("click", ".proceed-btn", function(event) {
      event.preventDefault();
      saveIncrement('{{route('checkoutpage.index')}}');
    });
    $('#cart-footer').on("click", ".continue-shop", function(event) {
      event.preventDefault();
      saveIncrement('{{route('shoppage.index')}}');
    });

    
    
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

@else
@php
    return false;
@endphp
@endif