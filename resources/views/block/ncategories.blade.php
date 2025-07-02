  @php
    $modelCategory = (new \App\Models\ShopCategory);
    $categories = $modelCategory->getCategoriesAll($onlyActive = true);
    $categoriesTop = $modelCategory->getCategoriesTop();
  @endphp
  @if ($categoriesTop->count())  
 
   <ul class="nav-list-newww">  
            <li class="nav--link-out">
                <a class="nav--linkss" href="{{ route('product.all') }}">All Product</a>
            </li>          
              @foreach ($categoriesTop as $key =>  $category)
              
              @if (!empty($categories[$category->id]))
                  <li  class="nav--link-out menu-has-submenu">

                  @else 

                  <li class="nav--link-out">

                  @endif
                       <a class="nav--linkss" href="{{ $category->getUrl() }}">{{ $category->name }}</a> 
                       @if (!empty($categories[$category->id]))
                       <span class="ddown-btn"></span>
                       <ul class="nav--link-submenu">

                        @foreach ($categories[$category->id] as $cateChild)
                            <li class="sub-menu--linkk ">
                                 <a  class="nav--linkss-sub" href="{{ $cateChild->getUrl() }}">{{ $cateChild->name }}</a>
                            </li>
                        @endforeach
                      </ul>
                      @endif
                    </li>           
              
            @endforeach

            
             </ul>
         
  @endif
