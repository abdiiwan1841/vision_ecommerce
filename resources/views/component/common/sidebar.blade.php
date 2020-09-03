<nav id="sidebar">
    <a href="{{ route('admin.dashboard') }}">
    <div class="sidebar-header">
        <div class="row">
            <div class="col-lg-3">
                <img  width="50px" src="{{asset('public/uploads/user/thumb/'.Auth::user()->image)}}" alt="">
            </div>
            <div class="col-lg-9">
            <h5>{{Auth::user()->name}}  <br><strong style="font-size: 10px">( {{Auth::user()->role->name}} )</strong></h5>
            
            </div>
           

        </div>
        
        
    </div>
</a>

    <ul class="list-unstyled components">

        <li class="{{Request::is('admin/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> E-commerce  Dashboard</a>
        </li>
        <li class="{{Request::is('admin/inventory/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('admin.inventorydashboard') }}"><i class="fas fa-chart-line"></i> Inventory  Dashboard</a>
        </li>

        <li class="{{Request::is('admin/stock*') ? 'active' : '' }}">
            <a href="{{ route('stock.index') }}"> <i class="fa fa-cubes"></i> Stock</a>
        </li>
    
    
        <li class="{{Request::is('admin/damages*') ? 'active' : '' }}">
            <a href="{{ route('damages.index') }}"><i class="fas fa-trash-alt"></i>  Damage &amp; Samples</a>
        </li>

        @if(Auth::user()->role->id != 4)
      
        @if(Auth::user()->role->id == 1 || Auth::user()->role->id == 2 )
        <li class="{{Request::is('admin/generaloption*') ? 'active' : '' }}">
            <a href="{{route('generaloption.index')}}"> <i class="fas fa-filter"></i>General Options</a>
        </li>
        @endif
        
        

        <li class="">
            <a href="#product_section" data-toggle="collapse" aria-expanded="{{Request::is('admin/product_section*') ? 'true' : '' }}" class="dropdown-toggle"> <i class="fas fa-box-open"></i> Product Section</a>
            <ul class="collapse list-unstyled {{Request::is('admin/product_section*') ? 'active collapse show' : '' }}" id="product_section">

                <li class="{{Request::is('admin/product_section/products*') ? 'active' : '' }}">
                    <a href="{{route('products.index')}}"> <i class="fas fa-box-open"></i> Products</a>
                </li>
                
               
                <li class="{{Request::is('admin/product_section/brands') ? 'active' : '' }}">
                    <a href="{{route('brands.index')}}"> <i class="fas fa-band-aid"></i>Product Brands</a>
                </li>
                <li class="{{Request::is('admin/product_section/tags') ? 'active' : '' }}">
                    <a href="{{route('tags.index')}}"> <i class="fas fa-tags"></i>Product Tags</a>
                </li>
                <li class="{{Request::is('admin/product_section/categories') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('categories.index')}}"> <i class="fa fa-bullseye"></i>Product Category</a>
                </li>
                <li class="{{Request::is('admin/product_section/sizes*') ? 'active' : '' }}">
                    <a href="{{route('sizes.index')}}"> <i class="fas fa-window-maximize"></i>Product Sizes</a>
                </li>
                <li class="{{Request::is('admin/product_section/subcategories') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('subcategories.index')}}"><i class="fa fa-subway"></i>Product Types</a>
                </li>

            </ul>
        </li>

       
        
        
        
        
        <li class="">
            <a href="#Frontend" data-toggle="collapse" aria-expanded="{{Request::is('admin/ecom*') ? 'true' : '' }}" class="dropdown-toggle"> <i class="fab fa-opencart"></i> ECOMMERCE</a>
            <ul class="collapse list-unstyled {{Request::is('admin/ecom*') ? 'active collapse show' : '' }}" id="Frontend">
        
        
                <li class="{{Request::is('admin/ecom/advertisement*') ? 'active' : '' }}">
                    <a href="{{route('advertisement.index')}}"> <i class="fas fa-ad"></i> Advertisement</a>
                </li>
        
               
        
                <li class="{{Request::is('admin/ecom/area*') ? 'active' : '' }}">
                    <a href="{{ route('area.index') }}"> <i class="fas fa-map-marker-alt"></i> Area</a>
                </li>
                
        
                <li class="{{Request::is('admin/ecom/customers*') ? 'active' : '' }}">
                    <a href="{{route('ecomcustomer.index')}}"> <i class="fas fa-user"></i> Customer</a>
                </li>
                <li class="{{Request::is('admin/comments*') ? 'active' : '' }}">
                    <a href="{{route('comments.index')}}"> <i class="fas fa-quote-left"></i> Comments</a>
                </li>
               
        
            
                <li class="{{Request::is('admin/ecom/deliveryinfo*') ? 'active' : '' }}">
                    <a href="{{route('deliveryinfo.index')}}"> <i class="fas fa-truck"></i> Delivery</a>
                </li>
        
                <li class="{{Request::is('admin/ecom/deals*') ? 'active' : '' }}">
                    <a href="{{route('deals.index')}}"> <i class="fas fa-bullhorn"></i> Deals</a>
                </li>
        
                <li class="{{Request::is('admin/ecom/district*') ? 'active' : '' }}">
                    <a href="{{ route('district.index') }}"> <i class="fas fa-location-arrow"></i> District </a>
                </li>
        
                <li class="{{Request::is('admin/ecom/menus*') ? 'active' : '' }}">
                    <a href="#Menu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> <i class="fas fa-bars"></i> Frontend Menus</a>
                    <ul class="collapse list-unstyled" id="Menu">
                     
                    <li>
                    <a class="nav-link" href="{{ route('admin.menus').'?menu=1'}}">Footer Second Column</a>
                    </li>
                      
                   
              
                        
                    </ul>
        
                </li>
        
                <li class="{{Request::is('admin/ecom/charge') ? 'active' : '' }}">
                    <a href="{{ route('charge.index') }}"><i class="fas fa-credit-card"></i>All Charges </a>
                </li>
               
               
        
           
        
                <li class="{{Request::is('admin/ecom/pages*') ? 'active' : '' }}">
                    <a href="{{route('pages.index')}}"> <i class="fas fa-file"></i> Pages</a>
                </li>
        
               
        
            
               
               
               
                      
                <li class="{{Request::is('admin/ecom/return*') ? 'active' : '' }}">
                    <a href="{{ route('return.index') }}"> <i class="fas fa-undo"></i> Returns</a>
                </li>
        
               
        
        
        
        
               
              
                <li class="{{Request::is('admin/ecom/order*') ? 'active' : '' }}">
                    <a href="{{ route('order.index') }}"><i class="fas fa-clipboard-check"></i> Orders</a>
                </li>
        
                <li class="{{Request::is('admin/ecom/paymentmethod') ? 'active' : '' }}">
                    <a href="{{ route('paymentmethod.index') }}"> <i class="fab fa-cc-amazon-pay"></i> Payment Method</a>
                </li>
        
                <li class="{{Request::is('admin/ecom/sliders') ? 'active' : '' }}">
                    <a href="{{route('sliders.index')}}"><i class="fas fa-sliders-h"></i> Sliders</a>
                </li>
        
                
              
               
               
               
        
                
            </ul>
        
        </li>
        
        <li class="">
            <a href="#pos" data-toggle="collapse" aria-expanded="{{Request::is('admin/pos*') ? 'true' : '' }}" class="dropdown-toggle"> <i class="fas fa-truck-moving"></i> INVENTORY SECTION</a>
            <ul class="collapse list-unstyled {{Request::is('admin/pos*') ? 'active collapse show' : '' }}" id="pos">
            
                        
        <li class="{{Request::is('admin/pos/customers*') ? 'active' : '' }}">
            <a href="{{ route('customers.index') }}"><i class="fas fa-user-friends"></i> Inventory Customer</a>
        </li>
        <li class="{{Request::is('admin/pos/prevdue*') ? 'active' : '' }}">
            <a href="{{ route('prevdue.index') }}"><i class="fas fa-search-dollar"></i> Previous Due</a>
        </li>
        <li class="{{Request::is('admin/pos/sale*') ? 'active' : '' }}">
            <a href="{{ route('sale.index') }}"><i class="fas fa-people-carry"></i>Sales</a>
        </li>
        <li class="{{Request::is('admin/pos/cash*') ? 'active' : '' }}">
            <a href="{{ route('cash.index') }}"><i class="fas fa-hand-holding-usd"></i>Cashes</a>
        </li>
        <li class="{{Request::is('admin/pos/returnproduct*') ? 'active' : '' }}">
            <a href="{{ route('returnproduct.index') }}"><i class="fas fa-undo"></i>Returns</a>
        </li>
        
            
            </ul>
        
        </li>
        
        @if(Auth::user()->role->id == 1 || Auth::user()->role->id == 2)
        <li class="{{Request::is('admin/company*') ? 'active' : '' }}">
            <a href="{{ route('company.index') }}"><i class="fas fa-building"></i> Company Information</a>
        </li>
        @endif
        <li class="{{Request::is('admin/allprice*') ? 'active' : '' }}">
            <a href="{{ route('price.index') }}"><i class="fas fa-dollar-sign"></i> Update Ecommerce Price</a>
        </li>
        
        <li class="{{Request::is('admin/tp*') ? 'active' : '' }}">
            <a href="{{ route('tp.index') }}"><i class="fas fa-money-bill-alt"></i> Update Trade Price</a>
        </li>
        
        <li class="{{Request::is('admin/purchase*') ? 'active' : '' }}">
        <a href="{{ route('purchase.index') }}"> <i class="fas fa-store"></i> Purchase</a>
        </li>
        
  
        
            


            <li class="">
            <a href="#suppliersection" data-toggle="collapse" aria-expanded="{{Request::is('admin/suppliersection*') ? 'true' : '' }}" class="dropdown-toggle"> <i class="fas fa-hospital-user"></i> Suppliers Section</a>
            <ul class="collapse list-unstyled {{Request::is('admin/suppliersection*') ? 'active collapse show' : '' }}" id="suppliersection">
            
            <li class="{{Request::is('admin/suppliersection/suppliers') ? 'active' : '' }}">
                <a href="{{ route('suppliers.index') }}"> <i class="fas fa-hospital-user"></i> Suppliers</a>
            </li>

            <li class="{{Request::is('admin/suppliersection/payment') ? 'active' : '' }}">
                <a href="{{ route('payment.index') }}"><i class="fas fa-dollar-sign"></i> Payment</a>
            </li>
            <li class="{{Request::is('admin/suppliersection/supplierdue') ? 'active' : '' }}">
                <a href="{{ route('supplierdue.index') }}"><i class="fas fa-search-dollar"></i> Supplier Due</a>
            </li>

            </ul>

           </li>
        
            
           
        
            {{-- <li class="{{Request::is('admin/emp_type*') ? 'active' : '' }}">
                <a href="{{ route('emp_type.index') }}"><i class="fas fa-user-alt"></i>  Employee Type</a>
            </li>
            
            <li class="{{Request::is('admin/employee*') ? 'active' : '' }}">
                <a href="{{ route('employee.index') }}"><i class="fas fa-user-alt"></i>  Employee</a>
            </li> --}}
           
            @if(Auth::user()->role->id == 1)
        
            <li class="{{Request::is('admin/admininfo*') ? 'active' : '' }}">
                <a href="{{ route('admininfo.index') }}"><i class="fas fa-user-alt"></i>  Admin</a>
            </li>
            @endif
        
        
        
        
        <li class="{{Request::is('admin/report*') ? 'active' : '' }}">
            <a href="#user-outstanding" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> <i class="fa fa-info-circle"></i> Reports</a>
            <ul class="collapse list-unstyled {{Request::is('admin/report*') ? 'active collapse show' : '' }}" id="user-outstanding">
        
        
                <li class="{{Request::is('admin/report/pos/posuserstatement') ? 'active' : '' }}">
                    <a href="{{ route('report.posuserstatement') }}"><i class="fa fa-layer-group"></i> Inventory customer Statement</a>
                </li>
        
                <li class="{{Request::is('admin/report/pos/posdeatailstatement') ? 'active' : '' }}">
                    <a href="{{ route('report.posdetailstatement') }}"><i class="fa fa-layer-group"></i> Inventory customer Detail Statement</a>
                </li>
        
                
                <li class="{{Request::is('admin/report/pos/divisiowisenreport') ? 'active' : '' }}">
                    <a href="{{ route('report.divisionreport') }}"><i class="fa fa-layer-group"></i> Inventory Divisionwise Report</a>
                </li>
        
                <li class="{{Request::is('admin/report/stockreport') ? 'active' : '' }}">
                    <a href="{{ route('stockreport.report') }}"> <i class="fa fa-layer-group"></i> Stock Report</a>
                  </li>
        
                  <li class="{{Request::is('admin/report/ecom/ecomuserstatement') ? 'active' : '' }}">
                    <a href="{{ route('report.ecomuserstatement') }}"> <i class="fa fa-layer-group"></i> Ecommerce Customer Satements</a>
                  </li>
        
                  <li class="{{Request::is('admin/report/ecom/divisiowisenreport*') ? 'active' : '' }}">
                    <a href="{{ route('report.ecomdivisionreport') }}"><i class="fa fa-layer-group"></i> Ecommerce Divisionwise Report</a>
                </li>
                <li class="{{Request::is('admin/report/cashreport*') ? 'active' : '' }}">
                    <a href="{{ route('report.poscashreport') }}"><i class="fa fa-layer-group"></i> Cash Report</a>
                </li>

                <li class="{{Request::is('admin/report/pos/salesreport*') ? 'active' : '' }}">
                    <a href="{{ route('report.possalesreport') }}"><i class="fa fa-layer-group"></i>Inventory Sales Report According To Date</a>
                </li>

                <li class="{{Request::is('admin/report/supplierdue*') ? 'active' : '' }}">
                    <a href="{{ route('report.supplierdue') }}"><i class="fa fa-layer-group"></i>Supplier Due Report</a>
                </li>     
                
            </ul>
        
        </li>

        @endif


        <li class="{{Request::is('admin/action/changepassword') ? 'active' : '' }}">
            <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-directions"></i> Action</a>
            <ul class="collapse list-unstyled" id="homeSubmenu">
                <li>
                    <a class="nav-link" href="{{ route('admin.changepassword')}}">Change Password</a>
                </li>
                <li>
                    <a class="nav-link" href="{{ route('admin.profile')}}">Profile</a>
                </li>

                <li>
                    <a class="nav-link" href="{{ route('admin.logout') }}">Logout</a>
                </li>

                
            </ul>
            
        </li>

    </ul>

    <ul class="list-unstyled CTAs">
        
        <li>
            <a href="{{ route('admin.dashboard') }}" class="article">Back to Dashboard</a>
        </li>
    </ul>
</nav>