@extends($templatePath.'.shop_layout')

@section('main')

 <div class="container">
            <div class="loader-div">
                <div class="banner-sect-category"
                    style="background: url('{{ asset($pageData->image) }}');">
                    <div class="breadcrumb"><a href="{{ route('home') }}">Home </a> <span> <i class="fa fa-angle-right"></i></span>
                        {{ $pageData->title }}</div>
                        // mmmm
                    <h1 class="cat-title">{{ $pageData->title }}</h1>
                   <p class="content-category"> {!! sc_html_render($pageData->content) !!} </p>
                </div>
                <div class="row category px-2">
                    @if (!empty($itemsList))
                    @foreach ($itemsList as $item)
                    <div class="col-6 col-md-4 col-xl-3 mt-3 category-listing">
                        <div class="category-shop">
                          <a href="{{ $item->getUrl() }}">
                                <div class="card text-black featured_Products">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <h5 class="card-title">{{ $item->name }}</h5>
                                        </div>
                                    </div>
                                    <div class="featured_Products_image"><img src="{{ asset($item->getImage()) }}" alt="{{ $item->name }}"></div>
                                </div>
                            </a></div>
                    </div>
                    @endforeach
                  @endif                
                 
                    
                   
                    
                    
                </div>
                <ul class="pagination">
                     {{ $itemsList->appends(request()->except(['page','_token']))->links() }}
                </ul>
            </div>
        </div>
@endsection

@push('scripts')
  <script type="text/javascript">
    $('[name="filter_sort"]').change(function(event) {
      $('#filter_sort').submit();
    });
  </script>
@endpush
