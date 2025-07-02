  @php
    $modelCategory = (new \App\Models\ShopCategory);
    $categories = $modelCategory->getCategoriesAll($onlyActive = true);
    $categoriesTop = $modelCategory->getCategoriesTop();
  @endphp
  @if ($categoriesTop->count())  
 
   <ul class="menu_left_category">  
            <li class="nav-item has-submenu category">
                <a class="nav-link outer-cat" href="{{ route('product.all') }}">All Product</a>
            </li>          
              @foreach ($categoriesTop as $key =>  $category)
              
               
                  <li class="nav-item has-submenu category">
                       <a class="nav-link outer-cat" href="{{ $category->getUrl() }}">{{ $category->name }}</a> 
                       @if (!empty($categories[$category->id]))
                       <i class="fa fa-angle-down show-menu"></i>
                         <ul class="submenu collapse">

                        @foreach ($categories[$category->id] as $cateChild)
                            <li class="nav-item  category">
                                 <a  class="nav-link" href="{{ $cateChild->getUrl() }}">{{ $cateChild->name }}</a>
                                @if (!empty($categories[$cateChild->id]))
                                <ul>                                  
                                    @foreach ($categories[$cateChild->id] as $cateChild2)
                                        <li>
                                            <a class="nav-link" href="{{ $cateChild2->getUrl() }}">{{ $cateChild2->name }}</a>
                                        </li>
                                    @endforeach                                 
                                </ul>
                                 @endif
                            </li>
                        @endforeach
                      </ul>
                      @endif
                    </li>           
              
            @endforeach

            
             </ul>
             
              <h4>My Account</h4>
              <ul class="menu_left_category my-account-menu">     
                <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="{{ route('member.index') }}">dashboard</a> 
                </li> 
                <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="/sc_admin/vendor_order">my sales</a> 
                </li>
                <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="{{ route('member.order_list') }}">orders</a> 
                </li>                
                <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="{{ route('member.download') }}">purchases & downloads</a> 
                </li>
               <!--  <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="{{ route('member.change_address') }}">address</a> 
                </li> -->
                <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="/sc_admin/account-setting">account details</a> 
                </li>
                <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="/sc_admin/auth/setting">user profile</a> 
                </li>
                 <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="/sc_admin/agreement">Payments & agreements</a> 
                </li>
                <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="/sc_admin/product">My published models</a> 
                </li>
                <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="/sc_admin/product/create">publish new model</a> 
                </li>
                <li class="nav-item has-submenu category">
                 <a class="nav-link outer-cat" href="{{ route('logout') }}" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">logout</a> 
                </li>
              </ul>
              
              
  @endif
