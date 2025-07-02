@extends($templatePath.'.shop_layout')

@section('center')
<?php /*
  <div class="features_items">
    <h2 class="title text-center">{{ $title }}</h2>

    @isset ($itemsList)
      @if($itemsList->count())
      <div class="item-folder">
            @foreach ($itemsList as  $key => $item)
            <div class="col-sm-3 col-xs-4">
                <div class="item-folder-wrapper product-single">
                  <div class="single-products">
                    <div class="productinfo text-center product-box-{{ $item->id }}">
                      <a href="{{ $item->getUrl() }}"><img src="{{ asset($item->getThumb()) }}" alt="{{ $item->name }}" /></a>
                      <a href="{{ $item->getUrl() }}"><p>{{ $item->name }}</p></a>
                    </div>
                  </div>
                </div>
            </div>
            @endforeach
        <div style="clear: both; ">
        </div>
      </div>
      @endif
    @endisset

      @if (count($products) ==0)
        {{ trans('front.empty_product') }}
      @else
          @foreach ($products as  $key => $product)
          <div class="col-sm-4 col-xs-6">
              <div class="product-image-wrapper product-single">
                <div class="single-products">
                  <div class="productinfo text-center product-box-{{ $product->id }}">
                    <a href="{{ $product->getUrl() }}"><img src="{{ asset($product->getThumb()) }}" alt="{{ $product->name }}" /></a>

                    {!! $product->showPrice() !!}

                    <a href="{{ $product->getUrl() }}"><p>{{ $product->name }}</p></a>

                      @if ($product->allowSale())
                       <a class="btn btn-default add-to-cart" onClick="addToCartAjax('{{ $product->id }}','default')">
                         <i class="fa fa-shopping-cart"></i>{{trans('front.add_to_cart')}}
                      </a>
                      @else
                        &nbsp;
                      @endif
                  </div>
                      @if ($product->price != $product->getFinalPrice() && $product->kind != SC_PRODUCT_GROUP)
                      <img src="{{ asset($templateFile.'/images/home/sale.png') }}" class="new" alt="" />
                      @elseif($product->type == SC_PRODUCT_NEW)
                      <img src="{{ asset($templateFile.'/images/home/new.png') }}" class="new" alt="" />
                      @elseif($product->type == SC_PRODUCT_HOT)
                      <img src="{{ asset($templateFile.'/images/home/hot.png') }}" class="new" alt="" />
                      @elseif($product->kind == SC_PRODUCT_BUILD)
                      <img src="{{ asset($templateFile.'/images/home/bundle.png') }}" class="new" alt="" />
                      @elseif($product->kind == SC_PRODUCT_GROUP)
                      <img src="{{ asset($templateFile.'/images/home/group.png') }}" class="new" alt="" />
                      @endif
                </div>
                <div class="choose">
                  <ul class="nav nav-pills nav-justified">
                    <li><a  onClick="addToCartAjax({{ $product->id }},'wishlist')"><i class="fa fa-plus-square"></i>{{trans('front.add_to_wishlist')}}</a></li>
                    <li><a  onClick="addToCartAjax({{ $product->id }},'compare')"><i class="fa fa-plus-square"></i>{{trans('front.add_to_compare')}}</a></li>
                  </ul>
                </div>
              </div>
          </div>
          @endforeach
      @endif

    <div style="clear: both; ">
        <ul class="pagination">
          {{ $products->appends(request()->except(['page','_token']))->links() }}
      </ul>
    </div>
</div>
@endsection


@section('breadcrumb')
    <div class="breadcrumbs pull-left">
        <ol class="breadcrumb">
          <li><a href="{{ route('home') }}">{{ trans('front.home') }}</a></li>
          <li class="active">{{ $title }}</li>
        </ol>
      </div>
@endsection

@section('filter')
  <form action="" method="GET" id="filter_sort">
        <div class="pull-right">
        <div>
            @php
              $queries = request()->except(['filter_sort','page']);
            @endphp
            @foreach ($queries as $key => $query)
              <input type="hidden" name="{{ $key }}" value="{{ $query }}">
            @endforeach
          <select class="custom-select" name="filter_sort">
            <option value="">{{ trans('front.filters.sort') }}</option>
            <option value="price_asc" {{ ($filter_sort =='price_asc')?'selected':'' }}>{{ trans('front.filters.price_asc') }}</option>
            <option value="price_desc" {{ ($filter_sort =='price_desc')?'selected':'' }}>{{ trans('front.filters.price_desc') }}</option>
            <option value="sort_asc" {{ ($filter_sort =='sort_asc')?'selected':'' }}>{{ trans('front.filters.sort_asc') }}</option>
            <option value="sort_desc" {{ ($filter_sort =='sort_desc')?'selected':'' }}>{{ trans('front.filters.sort_desc') }}</option>
            <option value="id_asc" {{ ($filter_sort =='id_asc')?'selected':'' }}>{{ trans('front.filters.id_asc') }}</option>
            <option value="id_desc" {{ ($filter_sort =='id_desc')?'selected':'' }}>{{ trans('front.filters.id_desc') }}</option>
          </select>
        </div>
      </div>
  </form>
  */ ?>


  <div class="container width_int">
            <div class="loader-div">
                 <div>
                    <div class="breadcrumb"><a href="{{ route('home') }}">Home </a> <span> / </span>
                        {{ $title }}</div>
                     <h1 class="cat-title">{{ $title}}</h1>
                   
                </div>
                 <div class="row ">
                   @if (count($products) ==0)
                    <div class="search_page_ans"><h4><strong>No Product</strong></h4></div>
                  @else
                   @foreach ($products as  $key => $product_new)
			   
			   
				  
			        <!--<?php // if($product_new->name == NULL){ continue; } ?> --->
                    <?php  $pname = App\Models\ProductFiles::getFileExt($product_new['id']); ?>
                    <div class="col-6 col-md-3  with_hund_pt mt-3">
                        <div class="com_cover_div">
						
                           <a href="{{ $product_new->getUrl() }}">
                            <div class="text-black featured_Products">
                                <div class="featured_Products_image card ">
                                 <img src="{{ asset($product_new->getThumb()) }}" alt="{{ $product_new->name }}">
                                 
                                    <div class="Shop_now">
                                     <span class="pname"> @php
                                     echo strlen($pname) > 20 ? substr($pname,0,25)."..." : $pname;
                                  @endphp</span> <span>  {!! $product_new->showPrice() !!} </span>
								  
								  

								  
                                         
                                      </div>
									   
                                   
                                </div>
                            </div>
                            </a>
                            <div class="card-body">
                                <div class="text-center">
                                    <h5 class="card-title">@php
                                     echo strlen($product_new->name) > 20 ? substr($product_new->name,0,20).".." : $product_new->name;
                                  @endphp </h5>
								  @if($product_new->stock == 0)
								  <!--<div class="list-stock">
									  <?php if($product_new->stock == 0){ echo "Out of stock"; } ?>
								  </div>--->
								  @endif
                                  <div class="pro-details">
                                     <p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
                                     <div class="pro-content" style="display:none">
                                      @php
                                         echo substr($product_new->content, 0, 60).'...';
                                      @endphp
                                     </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach 
                    @endif                   
                   
                </div>
               <ul class="pagination">
                  {{ $products->appends(request()->except(['page','_token']))->links() }}
              </ul>
            </div>
        </div>

@endsection

@push('styles')
@endpush
@push('scripts')
  <script type="text/javascript">
    $('[name="filter_sort"]').change(function(event) {
      $('#filter_sort').submit();
    });
  </script>
@endpush
