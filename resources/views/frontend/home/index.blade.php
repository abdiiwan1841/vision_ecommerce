@extends('layouts.frontendlayout')
@section('title','Home')
@section('content')
@php
$colors = ["#eb4d4b", "#A3CB38", "#f1c40f", "#f39c12", "#2980b9", "#ff7979", "purple","#badc58","#78e08f","#079992"];      
@endphp


@if($g_opt_value['slider'] == 1) 
    <!-- Hero Section Begin -->
    <section class="hero-section">
        <div class="hero-items owl-carousel">
            @foreach ($sliders as $slider)
            <div class="single-hero-items set-bg" data-setbg="{{asset('public/uploads/sliders/scaled/'.$slider->image)}}">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                        
                        <h1 @if($slider->title_color != NULL) style="color:{{$slider->title_color}}" @endif>{{$slider->title}}</h1>
                            <p @if($slider->description_color != NULL) style="color:{{$slider->description_color}}" @endif>{{$slider->description}}</p>
                            <a style="background: {{$slider->button_color}} " href="{{$slider->button_link}}" class="primary-btn">{{$slider->button_text}}</a>
                        </div>
                    </div>
                    @if($slider->box_status == 1)
                <div class="off-card" style="background: {{$slider->box_color}}">
                        <h2>{{$slider->box_text}}</h2>
                    </div>
                    @endif
                    
                </div>
            </div>
            @endforeach

        </div>
    </section>
    <!-- Hero Section End -->
    @endif


    @if($deal != null)
    <!-- Deal Of The Week Section Begin-->

<section class="deal-of-week set-bg spad" style="background: {{$deal->bg_color}}">
<div class="container">
<div class="section-title">
  <h2>Current Offers</h2>
</div>
<div class="row">
<div class="col-lg-6">
  <img src="{{asset('public/uploads/deals/'.$deal->image)}}" alt="{{$deal->title}}">
</div>
  <div class="col-lg-6 text-center">
      <div class="section-title">
          <h2>{{$deal->title}}</h2>
          <p>{{$deal->description}}</p>
          <div class="product-price">
              Tk. {{$deal->amount}}
              <span>/ {{$deal->dealproduct->product_name}}</span>
          </div>
      </div>
      <div class="countdown-timer" id="countdown">
          <div class="cd-item">
              <span>56</span>
              <p>Days</p>
          </div>
          <div class="cd-item">
              <span>12</span>
              <p>Hrs</p>
          </div>
          <div class="cd-item">
              <span>40</span>
              <p>Mins</p>
          </div>
          <div class="cd-item">
              <span>52</span>
              <p>Secs</p>
          </div>
      </div>
      <a style="background: {{$deal->button_color}}" href="{{route('singleproduct.deal',$deal->product)}}" class="primary-btn">{{$deal->button_text}}</a>
  </div>
</div>
</div>
</section>
<!-- Deal Of The Week Section End -->

@endif



<div class="container-fluid">
<div class="row">  
<div class="col-lg-12">

  <div class="product-list banner-section spad">
    <div class="container-fluid">
      <div class="section-title">
        <h2>Our Products</h2>
      </div>
    <div class="row" id="dynamic-product">
      

      @foreach ($dynamic_products as $item)
          
  
      @php
 $in_stock = "";
      
if($item->in_stock == false){
        $in_stock = '<div class="stock-out">Stock Out</div>';
     }
     if($item->discount_price === null){
       $pd_price = '<div class="product-price">Tk.'.round($item->current_price).'</div>';
     }else{
       $pd_price = '<div class="product-price">Tk.'.round($item->current_price).'<span>Tk.'.round($item->current_price).'</span></div>';
     }
@endphp 
      <div class="col-6 col-md-2 col-lg-2">
        <div class="product-item">
            <div class="pi-pic">
            <img src="{{url('/')}}/public/uploads/products/thumb/{{$item->image}}" alt="{{$item->product_name}}">
            {!!$in_stock!!}
                <div class="icon">
                  <span class="badge badge-pink">{{$item->brand->brand_name}}</span> 
              </div>
                <ul>
                <li class="w-icon active homepd"><a id="pd-{{$item->id}}" href="javascript:void(0)"  class="add-to-cart" onclick="addToCart({{$item->id}},'{{$item->product_name}}','{{url('/')}}/public/uploads/products/tiny/{{$item->image}}',{{round($item->current_price)}},{{$item->in_stock}})"><i class=" icon_cart_alt"></i></a></li>
                    <li class="quick-view homepd"><a href="{{url('/')}}/product/{{$item->id}}">+ Details</a></li>
                </ul>
              </div>
            <div class="pi-text">
              <div class="catagory-name"><span style="color: purple">{{$item->subcategory->subcategory_name}}</span> - Size:  {{$item->size->name}} </div>
                <a href="{{url('/')}}/product/{{$item->id}}">
                    <h5>{{$item->product_name}}</h5>
                </a>
                {!!$pd_price!!}
            </div>
        </div>
        </div>

        @endforeach


      </div>
      <div style="width: 100%;text-align: center" >
      <a class="btn site-btn" id="moreproduct" href="javascript:void(0)" onclick="IncrementProduct()">Load More Products</a>
      </div>
      
       
      </div>
  
    </div>
  
  
  </div>



        <div class="col-lg-6">

          @if($g_opt_value['product_types'] == 1) 
        <!-- Product Types Section Begin -->
        <div class="banner-section spad">
          <div class="container-fluid">
            <div class="section-title ">
              <h2>Product Types</h2>
            </div>
              <div class="row">
               
                  @foreach ($subcategories as $subcat)
                  
                  <div class="col-6">
                  <a href="{{route('shoppage.subcategory',$subcat->id)}}">
                      <div class="single-banner">
                      <img class="img_leaf_shape" src="{{asset('public/uploads/product_type/frontend/'.$subcat->image)}}" alt="{{$subcat->subcategory_name}}">
                          <div class="inner-text">
                          <h5>{{$subcat->subcategory_name}}  @if($g_opt_value['product_types_counter'] == 1)  <span class="badge badge-danger">{{count(DB::table('products')->where('type','ecom')->where('subcategory_id',$subcat->id)->select('id')->get())}}</span> @endif </h5>
                          </div>
                      </div>
                    </a>
                  </div>
                
                  @endforeach
              </div>
          </div>
      </div>
      @endif
      
      @if($g_opt_value['brands'] == 1) 
      
              <!-- Brand  Section Begin -->
              <div class="banner-section no-pad-top">
                <div class="container-fluid">
                  <div class="section-title">
                    <h2>Brands</h2>
                  </div>
                    <div class="row">
                        @foreach ($brands as $brand)
                        
                        <div class="brand-list">
                        <a href="{{route('shoppage.brand',$brand->id)}}">
                            <div class="single-banner brand">
                           
                                <div class="inner-text">
                                <h5>{{$brand->brand_name}} @if($g_opt_value['brands_counter'] == 1) <span class="badge badge-warning">{{count(DB::table('products')->where('type','ecom')->where('brand_id',$brand->id)->select('id')->get())}}</span> @endif </h5>
                                </div>
                            </div>
                          </a>
                        </div>
                      
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Brand Section End -->
            @endif


    </div>

    
    <div class="col-lg-6">
      @if($g_opt_value['new_pd'] == 1) 
      <div class="product-list banner-section spad">
        <div class="container-fluid">
          <div class="section-title">
            <h2>New Product</h2>
          </div>
        <div class="row">
          
            @foreach ($new_products as $single_product)

            <div class="col-6">
                <div class="product-item">
                  
                    <div class="pi-pic">
                    <img src="{{asset('public/uploads/products/thumb/'.$single_product->image)}}" alt="{{$single_product->product_name}}">
                       
                      

                        @if($single_product->in_stock == 0)
                        <div class="stock-out">Stock Out</div>
                        @else
                        <div class="sale">Brand New</div>
                        @endif
    
                        <div class="icon">
                          <span class="badge badge-pink">{{$single_product->brand->brand_name}}</span> 
                      </div>
                   
            
                        <ul>
  
                     
                 
  
  
  
                            <li class="quick-view"><a href="{{route('singleproduct.index',$single_product->id)}}">+ View Details</a></li>
                            
                        </ul>
                      </div>
                 
                    <div class="pi-text">
                      <div class="catagory-name"><span style="color: {{$colors[rand(0,9)]}}">{{$single_product->subcategory->subcategory_name}}</span> - Size:  {{$single_product->size->name}} </div>
                        <a href="{{route('singleproduct.index',$single_product->id)}}">
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
                </div>
            @endforeach
          </div>
           
          </div>
  
        </div>
        @endif

        @if($g_opt_value['hot_pd'] == 1) 
        <div class="product-list banner-section no-pad-top">
          <div class="container-fluid">
            <div class="section-title">
              <h2>Hot Product</h2>
            </div>
          <div class="row">
            
              @foreach ($random_product as $single_product)
   
              <div class="col-6">
                  <div class="product-item">
                      <div class="pi-pic">
                      <img src="{{asset('public/uploads/products/thumb/'.$single_product->image)}}" alt="{{$single_product->product_name}}">
                      
         
                      @if($single_product->in_stock == 0)
                      <div class="stock-out">Stock Out</div>
                  
                      @endif
                          <div class="icon">
                            <span class="badge badge-pink">{{$single_product->brand->brand_name}}</span> 
                        </div>
                     
              
                          <ul>

    
    
                              <li class="quick-view"><a href="{{route('singleproduct.index',$single_product->id)}}">+ View Details</a></li>
                              
                          </ul>
                        </div>
                   
                      <div class="pi-text">
                        <div class="catagory-name"><span style="color: {{$colors[rand(0,9)]}}">{{$single_product->subcategory->subcategory_name}}</span> - Size:  {{$single_product->size->name}} </div>
                          <a href="{{route('singleproduct.index',$single_product->id)}}">
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
                  </div>
              @endforeach
            </div>
             
            </div>
    
          </div>

          @endif
  


  </div>
</div>
</div>






  
    @if($g_opt_value['pd_collection'] == 1) 
      <!-- Product Collection Begin -->
      <div class="banner-section spad">
        <div class="container-fluid">
          <div class="section-title">
            <h2>Product Collection</h2>
          </div>
            <div class="row">
                @foreach ($collections as $cat)
                
                <div class="col-6 col-lg-4 col-md-4">
                <a href="{{route('collection.view',$cat->id)}}">
                    <div class="single-banner">
                    <img src="{{asset('public/uploads/category/frontend/'.$cat->image)}}" alt="">
                        <div class="inner-text">
                            <h4>{{$cat->category_name}} @if($g_opt_value['collection_counter'] == 1) <span class="badge badge-danger">{{count(DB::table('products')->where('type','ecom')->where('category_id',$cat->id)->select('id')->get())}}</span> @endif </h4>
                        </div>
                    </div>
                  </a>
                </div>
              
                @endforeach
            </div>
        </div>
    </div>
    <!--  Section End -->
    @endif


  
@endsection

@push('js')

<script src="{{asset('public/assets/js/axios.min.js')}}"></script>
<script src="{{asset('public/assets/frontend/js/owl-2-filter.js')}}"></script>
<script>
var url = "{{url('/')}}";
var limit = 24;




function IncrementProduct(){
  $("#moreproduct").html('<i class="fa-2x fa fa-spinner fa-spin"></i> Loading.....');
  limit = limit+6;
  PopulateDynamicProduct(limit);
}

function PopulateDynamicProduct(limit){
axios.get(url+'/api/dynamicproduct/'+limit)
  .then(res => {
    let  pddata = res.data.dynamic_products;
    let  total_products = res.data.total_products;
      let  concatproductdata = "";
      pddata.forEach(function(data,key){
        let pd_price = "";
        let in_stock = "";
        if(data.in_stock == false){
           in_stock = '<div class="stock-out">Stock Out</div>';
        }
        if(data.discount_price === null){
          pd_price = `<div class="product-price">Tk.${Math.round(data.current_price)}</div>`;
        }else{
          pd_price = `<div class="product-price">Tk.${Math.round(data.current_price)}<span>Tk. ${Math.round(data.price)}</span></div>`;
        }
        concatproductdata += `<div class="col-6 col-md-2 col-lg-2">
          <div class="product-item">
              <div class="pi-pic">
              <img src="${url}/public/uploads/products/thumb/${data.image}" alt="${data.product_name}">
              ${in_stock}
                  <div class="icon">
                    <span class="badge badge-pink">${data.brand.brand_name}</span> 
                </div>
                  <ul>
                  <li class="w-icon active homepd"><a id="pd-${data.id}" href="javascript:void(0)"  class="add-to-cart" onclick="addToCart(${data.id},'${data.product_name}','${url}/public/uploads/products/tiny/${data.image}',${data.current_price},${data.in_stock})"><i class=" icon_cart_alt"></i></a></li>
                      <li class="quick-view homepd"><a href="${url}/product/${data.id}">+ Details</a></li>
                  </ul>
                </div>
              <div class="pi-text">
                <div class="catagory-name"><span style="color: purple">${data.subcategory.subcategory_name}</span> - Size:  ${data.size.name} </div>
                  <a href="${url}/product/${data.id}">
                      <h5>${data.product_name}</h5>
                  </a>
                  ${pd_price}
              </div>
          </div>
          </div>`;
    });
   $("#dynamic-product").html(concatproductdata);
    if(limit > total_products){
      $("#moreproduct").hide();
    }else{
      $("#moreproduct").html('Load More Product');
    }
    
  })

  .catch(res => {  
    console.log(res);
  })

}
    

    /*------------------
        Product Slider
    --------------------*/
    var owl = $(".product-slider").owlCarousel({
        loop: false,
        margin: 25,
        nav: true,
        items: 4,
        dots: true,
        navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
        smartSpeed: 1200,
        autoHeight: false,
        autoplay: true,
        responsive: {
            0: {
                items: 1,
            },
            576: {
                items: 2,
            },
            992: {
                items: 2,
            },
            1200: {
                items: 4,
            }
        }
    });



    /*---------------------
    Filter Owl
    -----------------------*/


$('.filter-control-2' ).on( 'click', '.product-item', function() {

var $item = $(this);
var filter = $item.data( 'owl-filter' )

owl.owlcarousel2_filter( filter );
$item.addClass( 'active' ).siblings().removeClass( 'active' );

} );


    var timerdate;
    $( document ).ready(function() {


    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    if(mm == 12) {
        mm = '01';
        yyyy = yyyy + 1;
    } else {
        mm = parseInt(mm) + 1;
        mm = String(mm).padStart(2, '0');
    }
    var timerdate = mm + '/' + dd + '/' + yyyy;
    // For demo preview end

    
    @if($deal != null)
    
    // Use this for real timer date
    var timerdate = "{{$deal->expired_at}}";

    @else

    var timerdate = "2020-06-05";
    
    @endif


          /*------------------
        CountDown
    --------------------*/

	$("#countdown").countdown(timerdate, function(event) {
        $(this).html(event.strftime("<div class='cd-item'><span>%D</span> <p>Days</p> </div>" + "<div class='cd-item'><span>%H</span> <p>Hrs</p> </div>" + "<div class='cd-item'><span>%M</span> <p>Mins</p> </div>" + "<div class='cd-item'><span>%S</span> <p>Secs</p> </div>"));
    });

  });

  function displayCart() {
    var cartArray = shoppingCart.listCart();
    var output = "";
    for(var i in cartArray) {
      output += "<tr>"
        +"<td class='si-pic'><img src='"+cartArray[i].image +"' alt=''></td>"
        + "<td class='si-text'><div class='product-selected'><p>"+cartArray[i].price+"x " + cartArray[i].count + " = "+Math.round(cartArray[i].total)+"</p><h6>"+cartArray[i].o_name +"</h6>" 
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
    $('.select-button').html('<a href="shop/cart" class="primary-btn view-card">VIEW CART</a><a href="shop/cart/checkout" class="primary-btn checkout-btn">CHECK OUT</a>');
    }else{
      $('.select-button a').attr('disabled', true);
      $('.select-button').hide();
      $('.select-total').html('No Products On the Cart');

    }

  }

</script>
<script src="{{asset('public/assets/frontend/js/homepagecart.js')}}"></script>
@endpush