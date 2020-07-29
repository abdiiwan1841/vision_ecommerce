@extends('layouts.frontendlayout')
@section('title','User Profile')
@section('content')
    
  
<div class="container">
  <div class="row spad">
    <div class="col-lg-3">
      <ul class="list-group proflie-sidemenu  mb-5">
        <li class="list-group-item"><a href="{{route('orders.show')}}">My Order</a></li>
      <li class="list-group-item"> <a href="{{route('profile.show')}}">My Profile</a> </li>
        <li class="list-group-item current">Edit Profile</li>
      <li class="list-group-item"> <a href="{{route('profile.changepassword')}}">Change Password</a> </li>
      </ul>
    </div>
    <div class="col-lg-9">
    <form action="{{route('profile.update')}}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-lg-6">
            
            <div class="form-group">
                <label for="name">Name<span>*</span></label>
            <input type="text" id="name" placeholder="Enter Your Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name',Auth::user()->name)}}">
      
                @error('name')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>


      
            <div class="form-group">
                <label for="email">Email<span>*</span></label>
                <input type="text" id="email" placeholder="Enter Your Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{old('email',Auth::user()->email)}}">
                @error('email')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
      
            <div class="form-group">
                <label for="phone">Phone<span>*</span></label>
            <input type="text" id="phone" placeholder="Enter Your phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{old('phone',Auth::user()->phone)}}">
                @error('phone')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
              <label for="image">Image (optional)</label>
          <input type="file" id="image" placeholder="Upload Your Profile Avatar" class="form-control @error('image') is-invalid @enderror" name="image">
              @error('image')
              <small class="form-error">{{ $message }}</small>
              @enderror


              <img class="mt-3 mb-3 img-thumbnail" src="{{asset('public/uploads/user/thumb/'.Auth::user()->image)}}" id="user_image" alt="">
          </div>

      

        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="address">Address<span>*</span></label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address"  rows="4" placeholder="Enter Your Addres">{{old('address',Auth::user()->address)}}</textarea>
                
                @error('address')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            
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
                <label for="district">District<span>*</span></label>
                <select name="district" id="district" class="form-control @error('district') is-invalid @enderror">
                    <option value="">Select District</option>
                   
                    
                </select>
                @error('district')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
      
            <div class="form-group">
                <label for="area">Area<span>*</span></label>
                <select name="area" id="area" placeholder="Select a Area" class="form-control @error('area') is-invalid @enderror" id="area">
                    <option value="">Select Area</option>
                </select>
                @error('area')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
            

        </div>
        <div class="col-lg-8">
            <div class="form-group">
                <button type="submit" class="btn btn-success">UPDATE</button>
            </div>
        </div>
      
    </div>
  </form>
    </div>
  </div>
</div>


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
function uerImageURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#user_image').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}
$("#image").change(function() {
  uerImageURL(this);
  $('#user_image').show();
});
  
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
var exist_division_id = '{{Auth::user()->division_id}}';
var exist_district_id = '{{Auth::user()->district_id}}';
var exist_area_id = '{{Auth::user()->area_id}}';

$("#division").val(exist_division_id).trigger('change');
var district_id = $("#district").val();



    var base_url = '{{url('/')}}';
    var output = '';
    var division_id = $("#division").val();
    

    if(division_id.length > 0){
        $.get("{{asset('')}}api/district/"+division_id, function(data, status){
            if(data.length>0){
            output = '';
            $(data).each(function(index,element){
                if(element.id == exist_district_id){
                    output += '<option value="'+element.id+'" selected>'+element.name+'</option>';
               
            }else{
                output += '<option value="'+element.id+'">'+element.name+'</option>';
            }
            });
                $("#district").html(output);
            }else{
                $("#district").html('<option value="">No Area Found</option>');
            }
        });
        }else{
            $("#area").html('<option value="">No Area Found</option>');
        }



     $.get("{{asset('')}}api/area/"+exist_district_id, function(data, status){
        if(data.length>0){
        output = '';
        $(data).each(function(index,areaelement){
            if(areaelement.id == exist_area_id){

                output += '<option value="'+areaelement.id+'" selected>'+areaelement.name+'</option>';
            }else{
                output += '<option value="'+areaelement.id+'">'+areaelement.name+'</option>';
            }
        
        });
        $("#area").html(output);
        }else{
            $("#area").html('<option value="">No Area Found</option>');
        }
    });

        
    $("#division").change(function(){
        var division_id = $("#division").val();
        if(division_id.length > 0){
          $.get("{{asset('')}}api/district/"+division_id, function(data, status){
            if(data.length>0){
            output = '';
            $(data).each(function(index,element){
               output += '<option value="'+element.id+'">'+element.name+'</option>';
            });
               $("#district").html(output);
               $("#district").val(null).trigger('change');
               $('#area').html('');
 
            }else{
                $("#district").html('<option value="">No Area Found</option>');
            }
        });
        }else{
          $("#area").html('<option value="">No Area Found</option>');
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






  var cartLink = '{{route('cartpage.index')}}';
  var checkoutLink = '{{route('checkoutpage.index')}}';
 
   function displayCart() {
       var cartArray = shoppingCart.listCart();
       var output = "";
       for(var i in cartArray) {
         output += "<tr>"
           +"<td class='si-pic'><img src='"+cartArray[i].image +"' alt=''></td>"
           + "<td class='si-text'><div class='product-selected'><p>"+cartArray[i].price+"x " + cartArray[i].count + "="+cartArray[i].total+" Tk</p><h6>"+cartArray[i].o_name +"</h6>" 
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
                            Swal.fire({
                icon: 'error',
                title: 'Stock Out',
                text: 'This Product Is Out Of Stock ',
                showConfirmButton: false,
                timer: 1000
                })
              return;
        }else{
            $('#pd-'+id).html('<i class="icon_check"></i>').css('background','#44bd32');
            var item = new Item(o_name,name, price, count, id,image,stock);
            cart.push(item);
            saveCart();
                        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Successfully Added To Cart',
            showConfirmButton: false,
            timer: 1500
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