@extends('layouts.frontendlayout')
@section('title',$single_product->product_name)
@section('content')

@php
$current_stock = $single_product->stock($single_product->id);    

@endphp
<div class="container">
<div class="row">
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="#"><i class="fa fa-home"></i> Home</a>
                    <span>Single Product</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->
<div class="container-fluid">
  <div class="row my-5">
    <div class="col-lg-9">


    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body">
          <div class="row ">
            <div class="col-lg-6">
           
            
              <a style="cursor: crosshair" class="gallery-popup" href="{{asset('public/uploads/products/original/'.$single_product->image)}}"><img src="{{asset('public/uploads/products/original/'.$single_product->image)}}" class="product-image" alt="{{$single_product->product_name}}"></a>
              
              @if(!empty($single_product->gallery_image))
              <h5 class="mt-3">Product Gallery </h5>
              <hr>
              <ul class="gallery_image">
               
                @php
                $gallery_image_array = json_decode($single_product->gallery_image) @endphp
                @foreach($gallery_image_array as $single_image)
                <li> <a class="gallery-popup" href="{{asset('public/uploads/gallery/'.$single_image)}}"><img style="width:115px;" class="img-thumbnail" src="{{asset('public/uploads/gallery/thumb/'.$single_image)}}" class="product-image" alt="Product Image"></a> </li>
                @endforeach

               

              </ul>
              @endif
          

            </div>
            <div class="col-lg-6">
              <h3 class="my-3">{{$single_product->product_name}}</h3>
              
              <table class="table table-bordered">
                  <tr>
                    <th>Product Brand:</th>
                    <th>{{$single_product->brand->brand_name}}</th>
                  </tr>
                  <tr>
                    <th>Product Collection:</th>
                    <th>{{$single_product->category->category_name}}</th>
                  </tr>
                  <tr>
                    <th>Product Type:</th>
                    <th>{{$single_product->subcategory->subcategory_name}}</th>
                  </tr>
                  <tr>
                    <th>Product Size:</th>
                    <th>{{$single_product->size->name}}</th>
                  </tr>
              </table>
          
            
            
              <hr>



              <div class="product-price py-2 px-3">

  
                  @if($single_product->discount_price == NULL)
                  <h3 class="mb-0">
                  Tk.{{$single_product->price}}
                 </h3>
                  @else 
                  <h4 class="mt-0 price_heading">
                    <table class="table table-borderless">
                      <th>PRICE:</th>
                      <th style="color: #ff7979">Tk.{{$single_product->discount_price}} 
                        <small>Tk.{{$single_product->price}}</small></th>
                    </table>
                  
                  
                </h4>
                  @endif
     
              </div>

              <div>

                
    
                @if($single_product->in_stock == 0)
                  <span class="badge badge-danger">Sorry ! This Product Out Of Stock</span> 
                 
                @else 
                <a id="pd-{{$single_product->id}}" data-instock="{{$single_product->in_stock}}" data-name="{{$single_product->product_name}}" data-image="{{asset('public/uploads/products/tiny/'.$single_product->image)}}" data-id="{{$single_product->id}}" data-price="@if($single_product->discount_price == NULL) {{ $single_product->price}} @else {{ $single_product->discount_price}} @endif" href="#" class="add-to-cart site-btn"><i class=" icon_cart_alt"></i> Add To Cart</a>
                @endif
                
                
               
              </div>
              
            </div>
            
          </div>

        </div>
        <div class="card-body">
          <div class="pb-text">
            <h5><strong>Product Description:</strong></h5> <br>
            {!!$single_product->description!!}
          </div>

          <h5 class="mt-5"><strong>Comments:</strong></h5> <br>
          <div class="posted-by">
          @if(count($single_product->comments($single_product->id)) > 0)
          @foreach($single_product->comments($single_product->id) as $comment)
          
            <div class="pb-pic">
            <img style="width: 50px;" src="{{asset('public/assets/images/user.png')}}" alt="">
            </div>
            <div class="pb-text">
    
                <h5>{{$comment->name}}  </h5>
                <p style="color: #bbb"><small>{{$comment->created_at->format('d-M-Y g:i a')}}</small></p>
             
                <p>{{$comment->comments}}</p>
            </div>
        
        @endforeach
        @else
        <p>No Comments Found For This Product</p>
        @endif
      </div>
        <div class="leave-comment">
        @if(Session::has('success'))
        <div class="mt-5 mb-5">
      <span class="alert alert-success">{{Session::get('success')}}</span>
    </div>
      @else
          
            <h4 class="mb-5 mt-5">Leave A Comment</h4>
          <form action="{{route('comments.store',$single_product->id)}}" method="POST" class="comment-form">
            @csrf    
            <div class="row">
              @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                    <div class="col-lg-6">
                        <input type="text" placeholder="Name" name="name" required>
                    </div>
                    <div class="col-lg-6">
                        <input type="email" placeholder="Email" name="email" required>
                    </div>
                    <div class="col-lg-12">
                        <textarea placeholder="Messages" name="message" required></textarea>
                        <button type="submit" class="site-btn">Submit</button>
                    </div>
                </div>
            </form>
        
        @endif
      </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>

  <div class="col-lg-3">
      @if(count($simiar_product) > 0)
      <h4 class="mb-3 text-center text-uppercase">Similar Product</h4>
      @foreach ($simiar_product as $single_product)

              <div class="product-item">
                  <div class="pi-pic">
                      <img src="{{asset('public/uploads/products/thumb/'.$single_product->image)}}" alt="{{$single_product->product_name}}">
                  
                      @if($single_product->in_stock == 0)

                      <div style="background: #EA2027" class="sale pp-sale">Stock Out</div>
                       @else
                       <div class="sale"> Available</div>

                      @endif
                      <div class="icon">
                        <span class="badge badge-pink">{{$single_product->brand->brand_name}}</span> 
                    </div>
                 
          
                      <ul>
             
                          <li class="quick-view"><a href="{{route('singleproduct.index',$single_product->id)}}">+ View Details</a></li>
                          
                      </ul>
                    </div>
               
                  <div class="pi-text">
                      <div class="catagory-name">{{$single_product->subcategory->subcategory_name}}</div>
                      <a href="#">
                          <h5>{{$single_product->product_name}}</h5>
                      </a>
                      <div class="product-price">
                        @if($single_product->discount_price == NULL)
                        Tk.{{$single_product->price}}
                        @else Tk.{{$single_product->discount_price}}
                        <span>Tk.{{$single_product->price}}</span>
                        @endif
                      </div>
                  </div>

              </div>
          @endforeach
          @endif
  </div>
</div>
</div>
</div>
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('public/assets/css/util.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/frontend/css/magnific-popup.css')}}">

@endpush

@push('js')
<script src="{{asset('public/assets/frontend/js/jquery.magnific-popup.min.js')}}"></script>
<script>
  $('.gallery-popup').magnificPopup({
    type: 'image',
    mainClass: 'mfp-with-zoom', // this class is for CSS animation below

zoom: {
  enabled: true, 

  duration: 400,
  easing: 'ease-in-out', 

  opener: function(openerElement) {

    return openerElement.is('img') ? openerElement : openerElement.find('img');
  }
}
    // other options
    });




  var cartLink = '{{route('cartpage.index')}}';
    var checkoutLink = '{{route('checkoutpage.index')}}';
  
  function cancelOrder(id){
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success btn-sm',
            cancelButton: 'btn btn-danger btn-sm'
        },
        buttonsStyling: true
        })
  
  swalWithBootstrapButtons.fire({
  title: 'Are you sure Want to Cancel  Order ID  #'+id+'?',
  text: "Please Keep in mind You won't be able to revert this!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, Cancel it!'
  }).then((result) => {
        if (result.value) {
            event.preventDefault();
            document.getElementById('cancel-'+id).submit();
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
            'Cancelled',
            'Your Order  is safe :)',
            'error'
            )
        }
        });
    }
  
  
    
      
      function displayCart() {
        var cartArray = shoppingCart.listCart();
        var output = "";
        for(var i in cartArray) {
          output += "<tr>"
            +"<td class='si-pic'><img src='"+cartArray[i].image +"' alt=''></td>"
            + "<td class='si-text'><div class='product-selected'><p>"+cartArray[i].price+" x " + cartArray[i].count + "="+Math.round(cartArray[i].total)+"</p><h6>"+cartArray[i].o_name +"</h6>" 
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
@endpush