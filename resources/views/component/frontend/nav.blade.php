<div class="nav-item">
    <div class="container">
        @if($top_menu)

        
        <nav class="nav-menu mobile-menu">
            <ul>


        @foreach($top_menu as $menu)
                
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
              
            </ul>
        </nav>
        @endif
        <div id="mobile-menu-wrap"></div>
    </div>
</div>

