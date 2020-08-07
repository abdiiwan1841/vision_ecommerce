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

                <li  class="{{Request::is('pages*') ? 'active' : '' }}"><a href=""> <i class="fa fa-file"></i> Pages</a>
            
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