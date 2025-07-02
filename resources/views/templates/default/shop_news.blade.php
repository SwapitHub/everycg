@extends($templatePath.'.shop_layout')
@section('content')
<div class="container width_int news-col">
            <div class="loader-div">
                <div class="row news-inner">
                   <div class="banner-sect-category"
                    style="background: url('{{ asset($pageData->image) }}');">
                    <div class="breadcrumb"><a href="{{ route('home') }}">Home </a> <span> <i class="fa fa-angle-right"></i></span>
                        {{ $pageData->title }}</div>
                    <h2 class="cat-title">{{ $pageData->title }}</h2>
                   <p class="content-category"> {!! sc_html_render($pageData->content) !!} </p>
                  </div>
                   @if (!empty($news))
                    @foreach ($news as $newsDetail)
                    <div class="col-md-6 col-lg-4 col-xl-12 mt-3 news-listing">
                        <div class="text-black row">
                            <div class="col-md-12 col-xl-4 news-image-sect"> <img
                                    src="{{ asset($newsDetail->getThumb()) }}" alt="{{ $newsDetail->title }}"></div>
                            <div class="col-sm-12 col-xl-8 news-body">
                                <div class="text-center">
                                    <h5 class="card-title news-title">{{ $newsDetail->title }}</h5>
                                    <div class="date_time">
                                        <p><i class="fa fa-calendar"></i><?php echo date('M d, Y', strtotime($newsDetail->created_at)) ?></p>
                                        <p><i class="fa fa-clock-o"></i><?php echo date('h:i A', strtotime($newsDetail->created_at)); ?></p>
                                    </div>
                                     @php
                                     echo substr($newsDetail->content, 0, 500).'...';
                                  @endphp
                                    
                                    <div class="read-morenews"><a class="read-more" href="{{ $newsDetail->getUrl() }}">Read
                                            More</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                       @endforeach
                  @endif   
                    
                </div>
                <ul class="pagination">
                    {{ $news->links() }}
                </ul>
            </div>
        </div>



@endsection



@push('styles')
@endpush

@push('scripts')
<script>
   $('.selectpicker').selectpicker();
  $("select").change(function () {
    var option_all = $("select option:selected").map(function () {
        return $(this).text();
    }).get().join(',');
   $('.symbol').val(option_all);
   $( "#searchbox" ).submit();
});
</script>



@endpush
