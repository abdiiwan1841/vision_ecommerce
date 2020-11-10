@extends('layouts.adminlayout')

@section('title','Edit Inventory Customer')
@section('content')
<div class="row">
<div class="col-lg-12">
	<div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                <a class="btn btn-info btn-sm" href="{{route('customers.index')}}"><i class="fa fa-angle-left"></i> back</a>
                </div>
                <div class="col-lg-8">
                    <h5 class="text-right">EDIT - "<small>{{$customer->name}}</small>"</h5>
                </div>
            </div>
        </div>
    <div class="card-body">
        
    <form action="{{route('customers.update',$customer->id)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="row justify-content-center">
       
        <div class="col-lg-4">
            
            <div class="form-group">
                <label for="name">Name<span>*</span></label>
            <input type="text" id="name" placeholder="Enter Your Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name',$customer->name)}}" required>
      
                @error('name')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="proprietor">Proprietor Name<span>(optional)</span></label>
            <input type="text" id="proprietor" placeholder="Enter Proprietor Name" class="form-control @error('proprietor') is-invalid @enderror" name="proprietor" value="{{old('proprietor',$customer->proprietor)}}">
      
                @error('proprietor')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
      
            <div class="form-group">
                <label for="inventory_email">Email<span>(optional)</span></label>
                <input type="text" id="inventory_email" placeholder="Enter Your Email" class="form-control @error('inventory_email') is-invalid @enderror" name="inventory_email" value="{{old('inventory_email',$customer->inventory_email)}}">
                @error('inventory_email')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
      
            <div class="form-group">
                <label for="phone">Phone</label>
            <input type="text" id="phone" placeholder="Enter Your phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{old('phone',$customer->phone)}}">
                @error('phone')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="company">Company<span>(optional)</span></label>
            <input type="text" id="company" placeholder="Enter Your Company Name" class="form-control @error('company') is-invalid @enderror" name="company" value="{{old('company',$customer->company)}}">
                @error('company')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

      

        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label for="address">Address<span>*</span></label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address"  rows="4" placeholder="Enter Your Addres" required>{{old('address',$customer->address)}}</textarea>
                
                @error('address')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="division">Division<span>*</span></label>
                <select name="division" id="division" class="form-control @error('division') is-invalid @enderror" required>
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
              <label for="section">Customer Section<span>*</span></label>
              <select name="section" id="section" class="form-control @error('section') is-invalid @enderror" required>
                  <option value="">--Select Section--</option>
                  @foreach ($sections as $item)
                      <option value="{{$item->id}}" @if($item->id == $customer->section_id ) selected @endif >{{$item->name}}</option>
                  @endforeach
                  
              </select>
              @error('section')
              <small class="form-error">{{ $message }}</small>
              @enderror
          </div>
            

            <input type="hidden" id="pricedata" name="pricedata">
            

        </div>


        <div class="col-lg-8">

            <h5>Specify Some Product Price For  "{{$customer->name}}" </h5>
            <br>
            <div class="row">
                
                <div class="col-lg-7">
                    <div class="form-group">
                      <label for="product">Product</label>
                      <select data-placeholder="-select product-" class="js-example-responsive" name="product" id="product" class="form-control">
                      <option></option>
                       
                        @foreach ($products as $product)
                      <option value="{{$product->id}}">{{$product->product_name}}</option>    
                        @endforeach
      
                      </select>
                      <div class="product_err err_form"></div>
                     
                    </div>
                    <div class="form-group">
                      <span class="text-center" id="selected-product-info"></span>
                    </div>
      
                  </div>
      
      
      
              
                      <div class="col-lg-3">
                        
                        <div class="form-group">
                          <label for="price">Price</label>
                          <input type="text" class="form-control" name="price" id="price" placeholder="Enter Price">
                          <div class="price_err"></div>
                        </div>
                        
                       
                      </div>
      
        
                      <div class="col-lg-2">
                        <div style="margin-top: 31px">
                          <button type="button"  class="btn btn-warning  add-to-cart">ADD <i class="fa fa-plus"></i></button>
                        </div>
                        
                      </div>



                      <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                          <thead class="table-dark">
                            <tr>
                              <td>Sl.</td>
                              <td>Name</td>
                              <td>Price</td>
                              <td>Action</td>
                            </tr>
                          </thead>
                       
                          <tbody class="show-cart">
                  
                          </tbody>
                          
                        </table>
                      </div>
            
            
                        <hr>

                      
               
            </div>
            <div class="form-group">
                <button onclick="UserUpdate()" type="submit" class="btn btn-success">Update</button>
            </div>
        </div>
    
    </div>
</form>
    </div>
  </div>
</div>
</div>


@endsection

@push('css')

@endpush


@push('js')
<script>

var user_id = {{$customer->id}};

function UserUpdate(){
    sessionStorage.clear();
}


@if($pricedata === null)
  sessionStorage.clear();
@else
var pdata = {!!$pricedata!!}

if(sessionStorage.priceCart == undefined ){
    sessionStorage.setItem('priceCart',pdata);
    sessionStorage.setItem('user_id',user_id);
  }else if(sessionStorage.user_id != user_id){
      sessionStorage.clear();
      location.reload(true);
  }


@endif
if(sessionStorage.priceCart != undefined){
$("#pricedata").val(JSON.stringify(sessionStorage.priceCart));
}
function displayCart() {
  pd_output = '';
  var cartArray = priceCart.listCart();
  var j =1;
  for(var i in cartArray) {
    pd_output += "<tr>"
      + "<td>" + j++ + "</td>"
      + "<td>" + cartArray[i].o_name + "</td>"
      + "<td>" + cartArray[i].price + "</td>"
      + "<td><button class='delete-item btn btn-sm badge-danger' data-name=" + cartArray[i].name + ">X</button></td>"
      +  "</tr>";
  }
  $('.show-cart').html(pd_output);
  
}

$('#division').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a Division",
});
$('#product').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a Division",
});

var exist_division_id = '{{$customer->division_id}}';
var exist_district_id = '{{$customer->district_id}}';
var exist_area_id = '{{$customer->area_id}}';

$("#division").val(exist_division_id).trigger('change');
var district_id = $("#district").val();



    var base_url = '{{url('/')}}';
    var output = '';
    var division_id = $("#division").val();
  


function isNumber(n) { return !isNaN(parseFloat(n)) && !isNaN(n - 0) }

    // Toaster
  //Toater Alert 
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })


 // ************************************************
  // Shopping Cart API
  // ************************************************




var priceCart = (function() {
    // =============================
    // Private methods and propeties
    // =============================
    cart = [];
    
    // Constructor
    function Item(o_name,name, price, id) {
      this.o_name    = o_name;
      this.name = name;
      this.price = price;
      this.id = id;
      
    }
    
    // Save cart
    function saveCart() {
      sessionStorage.setItem('priceCart', JSON.stringify(cart));
    }

  function loadCart() {
    cart = JSON.parse(sessionStorage.getItem('priceCart'));
  }
  if (sessionStorage.getItem("priceCart") != null) {
    loadCart();
  }
    


    
  
    // =============================
    // Public methods and propeties
    // =============================
    var obj = {};
    
    // Add to cart
    obj.IncrementCart = function(name) {
      for(var item in cart) {
        if(cart[item].name === name) {
          cart[item].count ++;
          saveCart();
          return;
        }
      }
      var item = new Item(name);
      cart.push(item);
      saveCart();
    }
    

    obj.addItemToCart = function(o_name,name, price,id) {
      for(var item in cart) {
        if(cart[item].name === name) {
          Toast.fire({
            icon: 'error',
            title: '"'+o_name+'" Already Added'
          });

          return;
        }
      }



          var item = new Item(o_name,name, price,id);
          cart.push(item);
          saveCart();
          Toast.fire({
            icon: 'success',
            title: 'Successfully Added'
          });
      



    
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
            title: '<strong style="color: red">'+cart[item].name+'</strong> &nbsp; Removed Successfully'
          });
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
   var id = $("#product option:selected").val();
   var o_name = $("#product option:selected").text();
   var name =  o_name.replace(/\s/g, '');
   var price = $("#price").val();

   
  var err = [];

   if(price.length === 0){
    $("#price").addClass('is-invalid');
    $(".price_err").addClass('invalid-feedback').text('Price Field is Required');
    err.push('price');
  }else if(price < 1){
    $("#price").addClass('is-invalid');
    $(".price_err").addClass('invalid-feedback').text('Negative Number Not Allowed');
    err.push('price');
  }else if(isNumber(price) == false){
    $("#price").addClass('is-invalid');
    $(".price_err").addClass('invalid-feedback').text('Field Must Be Numeric');
    err.push('price');
  }else{
    $("#price").removeClass('is-invalid');
  }





  if(id.length === 0){
    $("#product + span").addClass("is-invalid");
    $(".product_err").removeClass('success_form').addClass('err_form').text('Product Field is Required');
    err.push('id');
  }else{
    $("#product + span").removeClass("is-invalid");
    $(".product_err").text('');
  }
  if(err.length<1){

    priceCart.addItemToCart(o_name,name, price,id);
    $("#product").val("").trigger("change");;
    $("#price").val("");
    $("#pricedata").val(JSON.stringify(sessionStorage.priceCart));

    displayCart();
  }
  });
  
  // Clear items
  $('.clear-cart').click(function() {
    priceCart.clearCart();
    displayCart();
  });
  
  



  
  // Delete item button
  
  $('.show-cart').on("click", ".delete-item", function(event) {
    var name = $(this).data('name')
    priceCart.removeItemFromCartAll(name);
    $("#pricedata").val(JSON.stringify(sessionStorage.priceCart));
    displayCart();
  })
  
  

  
  displayCart();


</script>
@endpush