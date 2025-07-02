@extends($templatePath.'.shop_layout')

@section('center')
<div class="container width_int search-page">
  <div class="row g-0">
            <div class="loader-div">
                 <div class="banner-sect-category"
                    style="background: url('{{ asset('/data/page/bg.jpg') }}');">
                    <div class="breadcrumb"><a href="{{ route('home') }}">Home </a> <span> <i class="fa fa-angle-right"></i></span>
                        {{ $title }}</div>
                    <h2 class="cat-title">Search Result "{{ $keyword }}"</h2>
                  
                </div>
                @if (count($products) ==0)  
                <div class=" search_page_ans"><h4><strong>Sorry, but nothing matched your search terms. Please try again with some different keywords.</strong></h4></div>
                @else

                 <div class="row ">
                   @foreach ($products as  $key => $product_new)
                   <?php  $pname = App\Models\ProductFiles::getFileExt($product_new['id']); ?>
                    <div class="col-6 col-md-4 col-xl-3 with_hund_pt mt-3">
                        <div class="com_cover_div">
                            <a href="{{ $product_new->getUrl() }}">
                            <div class="text-black featured_Products">
                                <div class="featured_Products_image card ">
                                    <img src="{{ asset($product_new->getThumb()) }}" alt="{{ $product_new->name }}">
                                   <div class="Shop_now">
                                      <span class="pname">@php
                                     echo strlen($pname) > 20 ? substr($pname,0,20)."..." : $pname;
                                  @endphp
                                        </span> 
                                        <span>  {!! $product_new->showPrice() !!} </span>
                                         
                                      </div>
                                    
                                </div>
                            </div>
                            </a>
                            <div class="card-body">
                                <div class="text-center">
                                    <h5 class="card-title">@php
                                         echo strlen($product_new->name) > 20 ? substr($product_new->name,0,20)."..." : $product_new->name;
                                      @endphp</h5>
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
                   
                </div>
               <ul class="pagination">
                  {{ $products->appends(request()->except(['page','_token']))->links() }}
              </ul>
               @endif
            </div>

            
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
