@extends('layouts.adminlayout')
@section('title','Create Purchase')
@section('content')

  <div class="row">
    <div class="col-lg-12">
     
        <div class="p_wrapper">
          <div class="row">
            <div class="col-lg-4"><a href="{{route('purchase.index')}}" class="btn btn-sm btn-primary"><i class="fa fa-angle-left"></i> back</a></div>
            <div class="col-lg-8">
               <strong class="float-right">CREATE PURCHASE INVOICE</strong> 
            </div>
          </div>
          <hr>
           <div class="row">
            @php
            $mytime = Carbon\Carbon::now();
        @endphp
            <div class="col-lg-4">
              <div class="form-group">
                <label for="purchase_date">Date</label>
               
                <input type="text" class="form-control" name="purchase_date" id="purchase_date" value="{{$mytime->toDateString()}}" readonly>
                <div class="date_err"></div>
              </div>


              <div class="form-group">
                <label for="supplier">Supplier </label>
                <select data-placeholder="-select supplier-" class="js-example-responsive" name="supplier" id="supplier" class="form-control">
                <option></option>
                 
                  @foreach ($suppliers as $supplier)
                <option value="{{$supplier->id}}">{{$supplier->name}}</option>    
                  @endforeach
    
                </select>
                <div class="supplier_err err_form"></div>
                
              </div>
              
            
          </div>


          <div class="col-lg-5">
            <div class="form-group">
              <div id="customer-details"></div>
            </div>
          </div>



           <div class="col-lg-3">
          <div class="card">
            
            <div class="card-header">
              
                <span class="float-left"><b>RESET FIELD</b></span> <button type="button" onclick="reset()" id="reset" class="btn btn-success float-right"><i class="fa fa-sync-alt"></i> </button>
              </div>
              
            </div>
          </div>
        </div>
          <hr>
          <div id="error">

          </div>
       
            <div class="row">
            <div class="col-lg-6 col-md-6">
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
             

            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <span class="text-center" id="selected-product-info"></span>
              </div>
            </div>



        
                <div class="col-lg-2 col-md-2">
                  
                  <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" name="price" placeholder="Enter Price" id="price">
                    <div class="price_err"></div>
                  </div>
                  
                 
                </div>

                <div class="col-lg-2 col-md-2">
                  <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="number" class="form-control" placeholder="Enter Qty" name="qty" id="qty">
                    <div class="qty_err"></div>
                  </div>
                </div>
                <div class="col-lg-2 col-md-2">
                <div class="form-group">
                  <label for="mfg">MFG</label>
                 
                  <input type="text" class="form-control" name="mfg" id="mfg" placeholder="Select MFG Date" readonly>
                  <div class="mfg_err"></div>
                </div>
              </div>

              <div class="col-lg-2 col-md-2">
                <div class="form-group">
                  <label for="exp">EXP</label>
                 
                  <input type="text" class="form-control" name="exp" id="exp" placeholder="Select EXP Date" readonly>
                  <div class="exp_err"></div>
                </div>
              </div>

              <div class="col-lg-2 col-md-2">
                <div class="form-group">
                  <label for="batch">Batch No.</label>
                 
                  <input type="text" class="form-control" name="batch" id="batch" placeholder="Enter batch Number">
                  <div class="batch_err"></div>
                </div>
              </div>
                <div class="col-lg-2 col-md-2">
                  <div style="margin-top: 31px">
                    <button type="button"  class="btn btn-primary  add-to-cart">ADD <i class="fa fa-plus"></i></button>
                  </div>
                  
                </div>
         
              
            </div>
        
              


        </div>
    </div>
    
    <div class="col-lg-12">
      <hr>
      <div class="p_detail_wrapper table-responsive">
        <h3 class="text-center">PURCHASE INVOICE</h3>
        <h5 class="date"></h5> <br>
   <br><br>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <td>Sl.</td>
            <td>Name</td>
            <td>Image</td>
            <td>Price</td>
            <td>Size</td>
            <td>Qty</td>
            <td>Mfg</td>
            <td>Exp</td>
            <td>Total</td>
            <td>Action</td>
          </tr>
        </thead>
     
        <tbody class="show-cart">

        </tbody>
        
      </table>
      </div>
      <div class="row">
      <div class="col-lg-7"></div>
      <div class="col-lg-5" id="amount-info">
        
        <table class="table table-bordered">
          <tr>
            <td>Subtotal</td>
            <td class="total-cart"></td>
          </tr>
          
          <tr>
            <td>Discount (%) <input type="number" class="form-control tiny-input" id="discount_input" value="0"></td>
            <td>Discount Amount <input type="text" class="discount form-control" value="0"></td>
          </tr>
          <tr>
            <td>Net Amount</td>
            <td class="net-amount"></td>
          </tr>
          <tr>
          <td>Carrying & Loading <input type="number" class="form-control tiny-input" id="carrying_and_loading" value="0"></td>
            <td class="carrying_and_loading">0</td>
          </tr>


          <tr>
            <td>Grand Total</td>
            <td class="grand-total"></td>
          </tr>

        </table>
  
          

  
      <button type="button" class="btn btn-primary"  id="confirm-btn" onclick="confirm_sales()">Confirm</button>

    </div>
    </div>
  </div>
  </div>

@endsection

@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/flatpicker.min.css')}}">
@endpush

@push('js')
<script>

function isNumber(n) { return !isNaN(parseFloat(n)) && !isNaN(n - 0) }

var baseuel = '{{url('/')}}';

  function reset(){
    sessionStorage.clear();
    $("#reset").html('<div class="fa-1x"><i class="fas fa-spinner fa-spin"></i></div>');
    location.reload(true);
  }

  $('#product').select2({
      width: '100%',
      theme: "bootstrap",
  });
  $('#supplier').select2({
      width: '100%',
      theme: "bootstrap",
  });
  if(sessionStorage.purchase_date != undefined){
     $('.date').html('Purchase  Date: '+sessionStorage.purchase_date);
  }
// If Shooping Cart Session Has Value Then Product Wrapeer Show
if(sessionStorage.purchaseCart != undefined){
  if(sessionStorage.purchaseCart.length > 2){
    $(".p_detail_wrapper").show();
  }else{
    $(".p_detail_wrapper").hide();
  }
}


if(sessionStorage.supplier_id!= undefined || sessionStorage.supplier_id!= undefined){
  $("#supplier").val(sessionStorage.supplier_id).trigger('change');
  $("#purchase_date").val(sessionStorage.purchase_date);
  $("#supplier").prop("disabled", true);
  $("#purchase_date").prop("readonly", false);
  $("#purchase_date").prop("disabled", true);
}





    // ************************************************
// Shopping Cart API
// ************************************************

function strip(number) {
    return (parseFloat(number).toPrecision(4));
}
//Precise Number 
function precise(x) {
  return Number.parseFloat(x).toPrecision(3);
}





//Sum Any Arrray

function sum(input){

    if (toString.call(input) !== "[object Array]")
    return false;

    var total =  0;
    for(var i=0;i<input.length;i++)
    {                  
    if(isNaN(input[i])){
    continue;
    }
    total += Number(input[i]);
    }
    return total;
}

var purchaseCart = (function() {
  // =============================
  // Private methods and propeties
  // =============================
  cart = [];
  
  // Constructor
  function Item(name, price, count, id,o_name,image,product_size,mfg,exp) {
    this.name = name;
    this.price = price;
    this.count = count;
    this.id    = id;
    this.o_name    = o_name;
    this.image    = image;
    this.product_size = product_size;
    this.mfg = mfg;
    this.exp = exp;
    
  }
  
  // Save cart
  function saveCart() {
    sessionStorage.setItem('purchaseCart', JSON.stringify(cart));
  }
  
    // Load cart
  function loadCart() {
    cart = JSON.parse(sessionStorage.getItem('purchaseCart'));
  }
  if (sessionStorage.getItem("purchaseCart") != null) {
    loadCart();
  }
  

  // =============================
  // Public methods and propeties
  // =============================
  var obj = {};
  
  // Add to cart
  obj.addItemToCart = function(name, price, count, id,o_name,image,product_size,mfg,exp) {
    for(var item in cart) {
      if(cart[item].name === name) {
       Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'This Product Already Aded'
        })
        return;
      }
    }
    var item = new Item(name, price, count, id,o_name,image,product_size,mfg,exp);
    cart.push(item);
    saveCart();
  }

  obj.IncrementCart = function(name, price, count, id,image,product_size) {
    for(var item in cart) {
      if(cart[item].name === name) {
        cart[item].count ++;
        saveCart();
        return;
      }
    }
    var item = new Item(name, price, count, id,image,product_size);
    cart.push(item);
    saveCart();
  }



  
  // Set count from item
  obj.setCountForItem = function(name, count) {
    for(var i in cart) {
      if (cart[i].name === name) {
        cart[i].count = count;
        saveCart();
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
    var totalCount = [];
    for(var item in cart) {
      totalCount.push(cart[item].count);
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
  
  var id = $("#product option:selected").val();
  var purchase_date = $("#purchase_date").val();
  var mfg = $("#mfg").val();
  var exp = $("#exp").val();
  var supplier_id = $("#supplier option:selected").val();
  var qnty = $("#qty").val();
  var o_name = $("#product option:selected").text();
  var nameSlulg =  o_name.replace(/\s/g, '');
  var price = $("#price").val();
  
  
  


  var err = [];
  if(qnty.length === 0){
    $("#qty").addClass('is-invalid');
    $(".qty_err").addClass('invalid-feedback').text('Qty Field is Required');
    err.push('qty');
  }else if(qnty < 1){
    $("#qty").addClass('is-invalid');
    $(".qty_err").addClass('invalid-feedback').text('Negative Number Not Allowed');
    err.push('qty');
  }else if(isNumber(qnty) == false){
    $("#qty").addClass('is-invalid');
    $(".qty_err").addClass('invalid-feedback').text('Field Must Be Numeric');
    err.push('qty');
  }else{
    $("#qty").removeClass('is-invalid');
  }
  if(purchase_date.length === 0){
    $("#purchase_date").addClass('is-invalid');
    $(".date_err").addClass('invalid-feedback').text('Purchase  Date Field is Required');
    err.push('purchase_date');
  }else{
    $("#purchase_date").removeClass('is-invalid');
  }
  if(mfg.length === 0){
    $("#mfg").addClass('is-invalid');
    $(".mfg_err").addClass('invalid-feedback').text('MFG  Date Field is Required');
    err.push('mfg_date');
  }else{
    $("#mfg").removeClass('is-invalid');
  }


  if(exp.length === 0){
    $("#exp").addClass('is-invalid');
    $(".exp_err").addClass('invalid-feedback').text('EXP  Date Field is Required');
    err.push('exp_date');
  }else{
    $("#exp").removeClass('is-invalid');
  }


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
  if(supplier_id.length === 0){
    $("#supplier + span").addClass("is-invalid");
    $(".supplier_err").text('User Field is Required');
    err.push('supplier_id');
  }else{
    $("#supplier + span").removeClass("err_form");
    $(".supplier_err").text('');
  }
  if(id.length === 0){
    $("#product + span").addClass("is-invalid");
    $(".product_err").removeClass('success_form').addClass('err_form').text('Product Field is Required');
    err.push('id');
  }else{
    $("#product + span").removeClass("is-invalid");
    $(".product_err").text('');
  }
  if(id.length > 0){
  $.get(baseuel+"/api/productinfo/"+id, function(data, status){
    if(status === 'success'){
      var image = data[0].image;
      var product_size = data[0].size.name;
      
    if(err.length<1){
    purchaseCart.addItemToCart(nameSlulg, price, qnty,id,o_name,image,product_size,mfg,exp);
    $(".is-valid").removeClass('is-valid');
    
    //Cart session has data
    if(sessionStorage.purchaseCart.length > 2){
    $(".p_detail_wrapper").show();
    }

    //If Order Session empty
    if (sessionStorage.getItem("supplier_id") == null || sessionStorage.getItem("purchase_date") == null) {
      sessionStorage.setItem("supplier_id",supplier_id);
      sessionStorage.setItem("purchase_date",purchase_date);
    }
    displayCart();
    //Supllier Input Disabled
    $("#supplier").prop("disabled", true);
    $("#purchase_date").prop("readonly", false);
    $("#purchase_date").prop("disabled", true);
    $("#product").val("").trigger("change");;
    $("#product + span").removeClass("is-valid");
    $(".product_err").text('');
    $("#price").val("");
    $("#qty").val("");
    $("#mfg").val("");
    $("#exp").val("");
    $("#selected-product-info").hide();
  }

  }
});
}
});


$( "#supplier" ).change(function() {
    var supplier_id = $("#supplier option:selected").val();
    $.get("{{url('/')}}/api/supplierinfo/"+supplier_id, function(data, status){
      if(status === 'success'){
  $("#customer-details").html('<p>Customer Details</p><table class="table table-bordered table-sm"><tr><th scope="col">Name</th> <td>'+data.name+'</td></tr><tr><th scope="col">Email</th><td>'+data.email+'</td></tr><tr><th scope="col">Phone</th><td>'+data.phone+'</td></tr><tr><th scope="col">Address</th><td>'+data.address+'</td></tr></table>');
        
      }
    });
});
$( "#product" ).change(function() {
    var product_id = $("#product option:selected").val();

    if(product_id.length > 0){

        $.get(baseuel+"/api/productinfo/"+product_id, function(data, status){
          if(status === 'success'){
              $("#selected-product-info").html('<table class="table table-sm table-hover"><tr><td> <b>'+data[0].product_name+'</b></td></tr><tr><td><img class="img-responsive img-thumbnail" src="'+baseuel+'/public/uploads/products/tiny/'+data[0].image+'" /></td></tr><tr><td> Price: '+data[0].current_price+'</td></tr><tr><td>Current Stock : '+data[1]+'</td></tr></table>');

            $("#selected-product-info").show();

          }
       
      });

      $("#product + span").removeClass('is-invalid').addClass('is-valid');
      $(".product_err").removeClass('err_form').addClass('success_form').text('Looks Good');
      
    }
   

});


$("#qty").change(function(){
  var qnty = $("#qty").val();
  if(qnty.length === 0){
    $("#qty").removeClass('is-valid').addClass('is-invalid');
    $(".qty_err").removeClass('valid-feedback').addClass('invalid-feedback');;
    $(".qty_err").text('Qty Field is Required');
    $(".add-to-cart").prop('disabled', true);
  }else if(isNumber(qnty) == false){
    $("#qty").removeClass('is-valid').addClass('is-invalid');
    $(".qty_err").removeClass('valid-feedback').addClass('invalid-feedback');
    $(".qty_err").text('Filed Must Be Numeric');
    $(".add-to-cart").prop('disabled', true);
  }else{
    $("#qty").removeClass('is-invalid').addClass('is-valid');
    $(".qty_err").removeClass('invalid-feedback').addClass('valid-feedback');
    $(".qty_err").text('Looks Good');
    $(".add-to-cart").prop('disabled', false);
}
});

$("#price").change(function(){
  var price = $("#price").val();
  if(price.length === 0){
    $("#price").removeClass('is-valid').addClass('is-invalid');
    $(".price_err").removeClass('valid-feedback').addClass('invalid-feedback');;
    $(".price_err").text('Qty Field is Required');
    $(".add-to-cart").prop('disabled', true);
  }else if(isNumber(price) == false){
    $("#price").removeClass('is-valid').addClass('is-invalid');
    $(".price_err").removeClass('valid-feedback').addClass('invalid-feedback');
    $(".price_err").text('Filed Must Be Numeric');
    $(".add-to-cart").prop('disabled', true);
  }else{
    $("#price").removeClass('is-invalid').addClass('is-valid');
    $(".price_err").removeClass('invalid-feedback').addClass('valid-feedback');
    $(".price_err").text('Looks Good');
    $(".add-to-cart").prop('disabled', false);
}
});



$("#purchase_date").change(function(){
  var od = $("#purchase_date").val();
  if(od.length === 0){
    $("#purchase_date").removeClass('is-valid').addClass('is-invalid');
    $(".date_err").removeClass('valid-feedback').addClass('invalid-feedback');;
    $(".date_err").text('Qty Field is Required');
  }else{
    $("#purchase_date").removeClass('is-invalid').addClass('is-valid');
    $(".date_err").removeClass('invalid-feedback').addClass('valid-feedback');
    $(".date_err").text('Looks Good');
}
});

$("#supplier").change(function(){
  var supplier = $("#supplier").val();
  if(supplier.length > 0){
    $("#supplier + span").removeClass('is-invalid').addClass('is-valid');
    $(".supplier_err").removeClass('err_form').addClass('success_form').text('Looks Good');
}
});




// Clear items
$('.clear-cart').click(function() {
  purchaseCart.clearCart();
  displayCart();
});

var sales_discount = $('#discount_input').val()/100;
var carrying_and_loading = $('#carrying_and_loading').val();

$("#discount_input").on("input",function(){
    var net_discount =  $('#discount_input').val();
    if(net_discount > 99){
      alert("Discount Amount Must Not Greater Than 100");
      $("#discount_input").val(0);
      $(".discount").val(0);
      displayCart();
    }else if(net_discount < 0){
      alert("Discount Amount Must Not Negative");
      $("#discount_input").val(0);
      $(".discount").val(0);
      displayCart();
    }else{
    sales_discount =  net_discount/100;
      var disc_amout = purchaseCart.totalCart()*sales_discount;
      $(".discount").val(Math.round(disc_amout));
      displayCart();
    }
  });

  $(".discount").on("input",function(){
    var net_discount_amount =  $('.discount').val();
    $("#discount_input").val('0');
    $(".discount").val(net_discount_amount);
    displayCart();
  });



  $("#carrying_and_loading").on("input",function(){
      if($('#carrying_and_loading').val() < 1){
        carrying_and_loading =  0;
        $('#carrying_and_loading').val(0);
      }else if($('#carrying_and_loading').val() < 0){
        alert('Must Not Be Negative');
        carrying_and_loading =  0;
        $('#carrying_and_loading').val(0);
        
      }else{
        carrying_and_loading =  $('#carrying_and_loading').val();
      }
     
      displayCart();
  });



function displayCart() {
  var discount_amount = $(".discount").val();
  var cartArray = purchaseCart.listCart();
  var baseuel = '{{url('/')}}';
  var output = "";
  var subtotal = purchaseCart.totalCart();
  var disc = subtotal*sales_discount;
  var netamount = subtotal-discount_amount;
  var g_total = parseFloat(netamount)+parseFloat(carrying_and_loading);
  var j =1;
  for(var i in cartArray) {
    output += "<tr>"
      + "<td>" + j++ + "</td>"
      + "<td>" + cartArray[i].o_name + "</td>"
      + "<td><img style='width: 50px;' src='"+baseuel+"/public/uploads/products/tiny/"+cartArray[i].image+"' class='img-thumbnail' /></td>"
      + "<td>" + cartArray[i].price + "</td>"
      + "<td>" + cartArray[i].product_size + "</td>"
      + "<td>"+ cartArray[i].count +"</td>"
      + "<td>"+ cartArray[i].mfg +"</td>"
      + "<td>"+ cartArray[i].exp +"</td>"
      + "<td>" + Math.round(cartArray[i].total) + "</td>" 
      + "<td><button class='delete-item btn btn-sm badge-danger' data-name=" + cartArray[i].name + ">X</button></td>"
      +  "</tr>";
  }
  $('.show-cart').html(output);
  $('.total-cart').html(purchaseCart.totalCart());

  $('.date').html('Purchase  Date: '+sessionStorage.purchase_date);
  $('.net-amount').text(Math.round(netamount));
  
  $('.discount').text( Math.round(disc));
  $('.carrying_and_loading').text(carrying_and_loading);
  $('.grand-total').html(Math.round(g_total));
}

// Delete item button

$('.show-cart').on("click", ".delete-item", function(event) {
  var name = $(this).data('name')
  purchaseCart.removeItemFromCartAll(name);
  displayCart();
  if(cart.length < 1){
    $(".p_detail_wrapper").hide();
  }
})


// -1
$('.show-cart').on("click", ".minus-item", function(event) {
  var name = $(this).data('name')
  purchaseCart.removeItemFromCart(name);
  displayCart();
})
// +1
$('.show-cart').on("click", ".plus-item", function(event) {
  var name = $(this).data('name')
  purchaseCart.IncrementCart(name);
  displayCart();
})

// Item count input
$('.show-cart').on("change", ".item-count", function(event) {
   var name = $(this).data('name');
   var count = Number($(this).val());
  purchaseCart.setCountForItem(name, count);
  displayCart();
});

displayCart();


// Ajax 

function confirm_sales(){
  $(document).ready(function () {
    var discount_amount = $(".discount").val();
 
   if(sessionStorage.purchaseCart.length < 3){
      alert('please select a product');
   }else{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
          data: {
            'purchase_date' : sessionStorage.purchase_date,
            'supplier_id' : sessionStorage.supplier_id,
            'discount' : discount_amount,
            'carrying_and_loading' : carrying_and_loading,
            'product' : sessionStorage.purchaseCart,
          },
          url: "{{route('purchase.store')}}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              console.log(data);
              sessionStorage.clear();
              var b_url = '{{url('/')}}';
              window.location = b_url+'/admin/purchase/'+data;
              
          },
          error: function (data) {
            console.log(data);
           sessionStorage.clear();
           if(data.status == 200){
              console.log(data.status);
           }else{
            
           var errdata = "";
           $.each(data.responseJSON.errors, function( key, value ) {
                    errdata += "<li>"+value+"</li>";
            });
            $('#error').html(errdata);
            $('#error').addClass('alert alert-danger');
           }

           
              
          }
      });
      $('#confirm-btn').attr('disabled',true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading.....' );
    }
  });
  
}


 

</script>

<script src="{{asset('public/assets/js/flatpicker.min.js')}}"></script>
<script>
  $("#purchase_date").flatpickr({dateFormat: 'Y-m-d', allowInput: true});
  $("#mfg").flatpickr({dateFormat: 'Y-m-d', allowInput: true});
  $("#exp").flatpickr({dateFormat: 'Y-m-d', allowInput: true});
</script>

@endpush


