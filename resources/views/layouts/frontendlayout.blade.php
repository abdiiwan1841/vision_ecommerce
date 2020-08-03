<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="{{$CompanyInfo->company_name}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

     <!-- Favicon-->
     <link rel="shortcut icon" href="{{asset('public/uploads/favicon/cropped/'.$CompanyInfo->favicon)}}">


    <title>@yield('title') - {{$CompanyInfo->company_name}}</title>


    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
         
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/bootstrap.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/themify-icons.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/elegant-icons.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/owl.carousel.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/nice-select.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/jquery-ui.min.css')}}" type="text/css">
    <link href="{{asset('public/assets/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    @stack('css')
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/slicknav.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/font-awesome.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/style.css')}}?v={{time()}}" type="text/css">
<link rel="stylesheet" href="{{asset('public/assets/frontend/css/custom.css')}}?v={{time()}}" type="text/css">
</head>

<body>
    @if($g_opt_value['pageloader'] == 1) 
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
    </div>
    @endif

    <!-- Header Section Begin -->
    <header class="header-section">
        <div class="header-top">
            <div class="container">
                <div class="ht-left">
                    <div class="mail-service">
                        <i class=" fa fa-envelope"></i>
                        {{$CompanyInfo->email}}
                    </div>
                    <div class="phone-service">
                        <i class=" fa fa-phone"></i>
                        {{$CompanyInfo->phone}}
                    </div>
                </div>
                <div class="ht-right">
                    <ul class="top_bar_link">
                        <li>
                        @if(Auth::check())
                        <a  class="login-panel" href="javascript:void(0);"> <i class="fa fa-user"></i>{{Auth::user()->name}} &nbsp; <i class="fa fa-caret-down"></i></a>
                        <ul>
                            <li><a href="{{route('profile.show')}}">My Profile</a></li>
                            <li><a href="{{route('orders.show')}}">My Order</a></li>
                            <li><form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <a href="{{ route('logout') }}" 
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"> Logout
                                </a>
                            </form></li>
                        </ul>
                    </li>
               
                        @else
                        <li>
                        <a href="{{route('login')}}" class="login-panel"><i class="fa fa-user"></i>Login</a></li>
                        @endif

                    <li> 
                <a href="{{route('admin.login')}}" class="login-panel"><i class="fa fa-cog"></i>Admin Login</a></li>
                   
                    <div class="top-social">
                        @foreach ($social as $key =>  $item)
                        @if($item[1] != 0)
                            <a href="{{$item[0]}}"><i class="ti-{{$key}}"></i></a>
                        @endif
                    @endforeach
                    </div>
                </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="inner-header">
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <div class="logo">
                            <a href="{{route('homepage.index')}}">
                                <img src="{{asset('public/uploads/logo/cropped/'.$CompanyInfo->logo)}}" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-7">
                        <form action="{{route('search.index')}}" method="GET">
                        <div class="advanced-search">

                            
                            <div class="input-group">
                                <input type="text" placeholder="What do you need?" id="autocomplete" name="s">
                                <button type="submit"><i class="ti-search"></i></button>
                            </div>
                            

                        </div>
                    </form>
                    </div>
                    <div class="col-lg-3 text-right col-md-3">
                        <ul class="nav-right">
                            <li class="cart-icon">
                                @if(Request::is('shop/cart*'))

                                @else
                                <a href="#">
                                    <i class="icon_cart_alt"></i>
                                    <span class="total-count"></span>
                                </a>
                                <div class="cart-hover ">
                                    <div class="select-items">
                                        <table>
                                            <tbody class="show-cart">


                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="select-total">
                                       
                                    </div>
                                    <div class="select-button">
                                        
                                    </div>
                                </div>
                                
                            </li>
                            <li class="cart-price"></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-item">
            <div class="container">
                <div class="nav-depart">
                    <div class="depart-btn {{Request::is('type*') ? 'active' : '' }}">
                        <i class="ti-menu"></i>
                        <span>Product Types</span>
                        <ul class="depart-hover">
                            @foreach ($product_subcategories as $subcat)
                        <li><a class="{{Request::is('type/'.$subcat->id) ? 'active' : '' }}" href="{{route('shoppage.subcategory',$subcat->id)}}">{{$subcat->subcategory_name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <nav class="nav-menu mobile-menu">
                    <ul>
                        <li  class="{{Request::is('/') ? 'active' : '' }}"><a href="{{route('homepage.index')}}"> <i class="fa fa-home"></i> Home</a></li>

                        <li  class="{{Request::is('/pages*') ? 'active' : '' }}"><a href=""> <i class="fa fa-file"></i> Pages</a>
                    
                        <ul class="dropdown">
                            @foreach ($page_list as $page)
                            <li><a href="{{route('frontendpage.index',$page->slug)}}">{{$page->page_title}}</a></li>
                            @endforeach
                        </ul>
                        </li>

                        <li class="{{Request::is('shop*') ? 'active' : '' }}"><a  href="{{route('shoppage.index')}}"> <i class="fa fa-shopping-bag"></i> Shop</a></li>
                        <li class="{{Request::is('collection*') ? 'active' : '' }}" ><a href="#"> <i class="fa fa-sitemap"></i> Collection</a>
                            <ul class="dropdown">
                                @foreach ($product_categories as $cat)
                                <li><a href="{{route('collection.view',$cat->id)}}">{{$cat->category_name}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    <li class="{{Request::is('contact') ? 'active' : '' }}"><a href="{{route('contactpage.index')}}"> <i class="fa fa-envelope"></i> Contact</a></li>


                    </ul>
                </nav>
                <div id="mobile-menu-wrap"></div>
            </div>
        </div>
    </header>
    <!-- Header End -->


    @yield('content')
    <section class="latest-blog">
        <div class="container">


    <div class="benefit-items">
        <div class="row">
            <div class="col-lg-4">
                <div class="single-benefit">
                    <div class="sb-icon">
                        <img src="{{asset('public/assets/frontend/img/icon-1.png')}}" alt="">
                    </div>
                    <div class="sb-text">
                        <h6>Fastest Shipping</h6>
                        <p>Assure you quality delivery</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="single-benefit">
                    <div class="sb-icon">
                        <img src="{{asset('public/assets/frontend/img/icon-2.png')}}" alt="">
                    </div>
                    <div class="sb-text">
                        <h6>Delivery On Time</h6>
                        <p>Assure you quality delivery</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="single-benefit">
                    <div class="sb-icon">
                        <img src="{{asset('public/assets/frontend/img/icon-1.png')}}" alt="">
                    </div>
                    <div class="sb-text">
                        <h6>Secure Payment</h6>
                        <p>100% secure payment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
    <!-- Footer Section Begin -->
    <footer class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer-left">
                        <div class="footer-logo">
                            <a href="#"><img src="{{asset('public/uploads/logo/cropped/'.$CompanyInfo->logo)}}" alt=""></a>
                        </div>
                        <ul>
                            <li>Address: {{$CompanyInfo->address}}</li>
                            <li>Phone: {{$CompanyInfo->phone}}</li>
                            <li>Email: {{$CompanyInfo->email}}</li>
                        </ul>
                        <div class="footer-social">
                            @foreach ($social as $key =>  $item)
                            @if($item[1] != 0)
                                <a href="{{$item[0]}}"><i class="ti-{{$key}}"></i></a>
                            @endif
                        @endforeach

                        </div>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1">
                    <div class="footer-widget">
                        <h5>Useful Links</h5>
                        <ul>
                    @if($footer_second_column_menu)
                            @foreach($footer_second_column_menu as $menu)
                                    
                                <li>
                                    <a href="{{ $menu['link'] }}">{{ $menu['label'] }}</a>
                                    @if( $menu['child'] )
                                    
                                            <ul class="dropdown {{ $menu['class'] }}">
                                                @foreach( $menu['child'] as $child )
                                                    <li class=""><a href="{{ $child['link'] }}" title="">{{ $child['label'] }}</a></li>
                                                @endforeach
                                            </ul><!-- /.sub-menu -->
                                    
                                    @endif
                                </li>
                            @endforeach
                            @endif

                            @foreach ($page_list as $page)
                            <li><a href="{{route('frontendpage.index',$page->slug)}}">{{$page->page_title}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer-widget">
                        <h5>My Account</h5>
                        <ul>
							   <li><a href="{{route('profile.show')}}">My Profile</a></li>
							   <li><a href="{{route('orders.show')}}">My Order</a></li>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="newslatter-item">
                        <h5>Join Our Newsletter Now</h5>
                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        <form action="#" class="subscribe-form">
                            <input type="text" placeholder="Enter Your Mail">
                            <button type="button">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-reserved">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="copyright-text">
                            
Copyright &copy;{{ now()->year }} {{$CompanyInfo->company_name}}  All rights reserved 
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="{{asset('public/assets/frontend/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('public/assets/frontend/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('public/assets/js/sweetalert2.min.js')}}"></script>
    <script src="{{ asset('public/storage/product.json')}}"></script>
    <script src="{{asset('public/assets/frontend/js/jquery.autocomplete.min.js')}}"></script>
    <script src="{{asset('public/assets/frontend/js/owl.carousel.min.js')}}"></script>
    
    


    @stack('js')    
    <script>
        var p_url = "{{url('/')}}";
    $('#autocomplete').autocomplete({
        lookup:  productJSON,
        onSelect: function (suggestion) {
            window.location= p_url+"/product/"+suggestion.data; 
            console.log(suggestion);
        },
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'Sorry, no matching results',
    });
    </script>
   <script src="{{asset('public/assets/frontend/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('public/assets/frontend/js/jquery.countdown.min.js')}}"></script>
    <script src="{{asset('public/assets/frontend/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{asset('public/assets/frontend/js/jquery.dd.min.js')}}"></script>
    <script src="{{asset('public/assets/frontend/js/jquery.slicknav.js')}}"></script>
    
    
    <script src="{{asset('public/assets/frontend/js/main.js')}}?v={{time()}}"></script>    
</body>

</html>