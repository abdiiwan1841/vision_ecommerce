@extends('layouts.adminlayout')

@section('title','Edit Inventory Customer')
@section('content')
<div class="row">
<div class="col-lg-12">
	<div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                <a class="btn btn-info btn-sm" href="{{route('marketingreport.index')}}"><i class="fa fa-angle-left"></i> back</a>
                </div>
                <div class="col-lg-8">
                    <h5 class="text-right">"EDIT" Marketing Sales Report</h5>
                </div>
            </div>
        </div>


        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
          <form action="{{route('marketingreport.update',$marketingreport->id)}}" method="POST">
              @csrf
              @method('PUT')
              <div class="form-group">
                <label for="at">Date</label>
                <input type="text" class="form-control @error('at') is-invalid @enderror" name="at" id="at" placeholder="Select  Date" value="{{old('at',$marketingreport->at)}}">
                    @error('at')
                    <small class="form-error">{{ $message }}</small>
                    @enderror
              </div>


                      <div class="form-group">
                        <label for="employee">employee</label>
                        <select name="employee" id="employee" class="form-control @error('employee') is-invalid @enderror" >
                            <option value="">Select employee</option>
            
                            @foreach ($employees as $item)
                            <option value="{{$item->id}}" @if($item->id == $marketingreport->employee_id) selected @endif>{{$item->name}}</option>
                            @endforeach
                            
                            
                        </select>
                        @error('employee')
                        <small class="form-error">{{ $message }}</small>
                        @enderror
                    </div>



      
                  <div class="form-group">
                      <label for="market">Market Name</label>
                  <input type="text" id="market" placeholder="Enter market Name" class="form-control @error('market') is-invalid @enderror" name="market" value="{{old('market',$marketingreport->market)}}">
            
                      @error('market')
                      <small class="form-error">{{ $message }}</small>
                      @enderror
                  </div>
      
      
                  <div class="form-group">
                    <label for="order">Order</label>
                <input type="text" id="order" placeholder="Enter order" class="form-control @error('order') is-invalid @enderror" name="order" value="{{old('order',$marketingreport->order)}}">
          
                    @error('order')
                    <small class="form-error">{{ $message }}</small>
                    @enderror
                </div>


                   
                <div class="form-group">
                  <label for="delivery">Delivery</label>
              <input type="text" id="delivery" placeholder="Enter delivery" class="form-control @error('delivery') is-invalid @enderror" name="delivery" value="{{old('delivery',$marketingreport->delivery)}}">
        
                  @error('delivery')
                  <small class="form-error">{{ $message }}</small>
                  @enderror
              </div>


                
                <div class="form-group">
                  <label for="area">area</label>
              <input type="text" id="area" placeholder="Enter Area" class="form-control @error('area') is-invalid @enderror" name="area" value="{{old('area',$marketingreport->area)}}" >
        
                  @error('area')
                  <small class="form-error">{{ $message }}</small>
                  @enderror
              </div>
      
             


              
              <div class="form-group">
                <label for="comment">Comment</label>

              <textarea name="comment"  class="form-control @error('area') is-invalid @enderror" id="comment" cols="30" rows="5">{{old('comment',$marketingreport->comment)}}</textarea>
            
      
                @error('comment')
                <small class="form-error">{{ $message }}</small>
                @enderror
            </div>


            <input type="hidden" id="productinfo" name="productinfo" value="">
          
          <div class="form-group">
              <button onclick="reportSubmit()" type="submit" class="btn btn-success">Update</button>
          </div>
      
        </form>

            </div>
        <div class="col-lg-6">
          

          
    <h5>Product Infomation (optional)</h5>
    <br>
    <div class="form-group">
      <label for="product">Product</label>
      <select data-placeholder="-select product-" class="js-example-responsive" name="product" id="product" class="form-control">
      <option></option>
      @foreach ($products as $item)
      <option value="{{$item->id}}">{{$item->product_name}}</option>
      @endforeach
        
      </select>
      <div class="product_err err_form"></div>
      
    </div>
    
    <div class="form-group">
      <span class="text-center" id="selected-product-info"></span>
    </div>

    
    <div class="form-group">
        <label for="qty">qty</label>
        <input type="text" class="form-control" name="qty" id="qty" placeholder="Enter qty">
        <div class="qty_err"></div>
    </div>
    

    <div class="mb-5 mt-5">
        <button type="button"  class="btn btn-warning btn-block add-to-cart">ADD <i class="fa fa-plus"></i></button>
    </div>

        


    <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <td>Sl.</td>
            <td>Name</td>
            <td>qty</td>
            <td>Action</td>
        </tr>
        </thead>
    
        <tbody class="show-cart">

        </tbody>
        
    </table>


        </div>





          </div>


   </div>
      </div>
    </div>
</div>

@endsection

@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/flatpicker.min.css')}}">
@endpush


@push('js')
<script src="{{asset('public/assets/js/flatpicker.min.js')}}"></script>
<script>
@if($marketingreport->productinfo === null)
  sessionStorage.clear();
@else
var pdata = {!!$marketingreport->productinfo!!}
var employeeid = {{$marketingreport->employee_id}}

if(sessionStorage.productinfoCart == undefined ){
    sessionStorage.setItem('productinfoCart',pdata);
    sessionStorage.setItem('employeeid',employeeid);
  }else if(sessionStorage.employeeid != employeeid){
      sessionStorage.clear();
      location.reload(true);
  }


@endif
  





$("#at").flatpickr({dateFormat: 'Y-m-d'});

function reportSubmit(){
    sessionStorage.clear();
}



if(sessionStorage.productinfoCart != undefined){
$("#productinfo").val(JSON.stringify(sessionStorage.productinfoCart));
}
function displayCart() {
  pd_output = '';
  var cartArray = productinfoCart.listCart();
  var j =1;
  for(var i in cartArray) {
    pd_output += "<tr>"
      + "<td>" + j++ + "</td>"
      + "<td>" + cartArray[i].o_name + "</td>"
      + "<td>" + cartArray[i].qty + "</td>"
      + "<td><button class='delete-item btn btn-sm badge-danger' data-name=" + cartArray[i].name + ">X</button></td>"
      +  "</tr>";
  }
  $('.show-cart').html(pd_output);
  
}


$('#employee').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a Employee",
});
$('#product').select2({
    width: '100%',
    theme: "bootstrap",
    placeholder: "Select a Product",
});




    var base_url = '{{url('/')}}';


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




var productinfoCart = (function() {
    // =============================
    // Private methods and propeties
    // =============================
    cart = [];
    
    // Constructor
    function Item(o_name,name, qty, id) {
      this.o_name    = o_name;
      this.name = name;
      this.qty = qty;
      this.id = id;
      
    }
    
    // Save cart
    function saveCart() {
      sessionStorage.setItem('productinfoCart', JSON.stringify(cart));
    }

  function loadCart() {
    cart = JSON.parse(sessionStorage.getItem('productinfoCart'));
  }
  if (sessionStorage.getItem("productinfoCart") != null) {
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
    

    obj.addItemToCart = function(o_name,name, qty,id) {
      for(var item in cart) {
        if(cart[item].name === name) {
          Toast.fire({
            icon: 'error',
            title: '"'+o_name+'" Already Added'
          });

          return;
        }
      }



          var item = new Item(o_name,name, qty,id);
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
        totalCart += cart[item].qty * cart[item].count;
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
        itemCopy.total = Number(item.qty * item.count).toFixed(2);
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
   var qty = $("#qty").val();

   
  var err = [];

   if(qty.length === 0){
    $("#qty").addClass('is-invalid');
    $(".qty_err").addClass('invalid-feedback').text('qty Field is ');
    err.push('qty');
  }else if(qty < 1){
    $("#qty").addClass('is-invalid');
    $(".qty_err").addClass('invalid-feedback').text('Negative Number Not Allowed');
    err.push('qty');
  }else if(isNumber(qty) == false){
    $("#qty").addClass('is-invalid');
    $(".qty_err").addClass('invalid-feedback').text('Field Must Be Numeric');
    err.push('qty');
  }else{
    $("#qty").removeClass('is-invalid');
  }





  if(id.length === 0){
    $("#product + span").addClass("is-invalid");
    $(".product_err").removeClass('success_form').addClass('err_form').text('Product Field is ');
    err.push('id');
  }else{
    $("#product + span").removeClass("is-invalid");
    $(".product_err").text('');
  }
  if(err.length<1){

    productinfoCart.addItemToCart(o_name,name, qty,id);
    $("#product").val("").trigger("change");;
    $("#qty").val("");
    $("#productinfo").val(JSON.stringify(sessionStorage.productinfoCart));

    displayCart();
  }
  });
  
  // Clear items
  $('.clear-cart').click(function() {
    productinfoCart.clearCart();
    displayCart();
  });
  
  



  
  // Delete item button
  
  $('.show-cart').on("click", ".delete-item", function(event) {
    var name = $(this).data('name')
    productinfoCart.removeItemFromCartAll(name);
    $("#productinfo").val(JSON.stringify(sessionStorage.productinfoCart));
    displayCart();
  })
  
  

  
  displayCart();


</script>
@endpush