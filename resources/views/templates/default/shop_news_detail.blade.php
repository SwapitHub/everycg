@extends($templatePath.'.shop_layout')

@section('main')


 <div class="container width_int news_detail_page">
            <div class="news_detail_page_sed">
                <div class="row g-0 news-detail-innr">
                    <div class="col-md-4 border-end left-sect">
                        <div class="main_image"><img src="{{ asset($news_currently->getThumb()) }}" alt="{{ $news_currently->title }}">
                        </div>
                    </div>
                    <div class="col-md-8 right-sect">
                        <div class="p-3 right-side">
                            <h2>{{ $news_currently->title }}</h2>
                            <div class="date_time">
                                <p><i class="fa fa-calendar"></i><?php echo date('M d, Y', strtotime($news_currently->created_at)) ?></p>
                                <p><i class="fa fa-clock-o"></i><?php echo date('h:i A', strtotime($news_currently->created_at)); ?></p>
                            </div>
                            <div class="mt-2 pr-3 content">
                               {!! sc_html_render($news_currently->content) !!} 
                            </div>
                        </div>
                    </div>
                </div>
                @if (count($related) > 0)               
                <div class="related_news row">
                    <h3>Related News</h3>
                    @foreach ($related as $newsDetail)
                     <div class="col-md-6 col-lg-4 col-xl-12 mt-3 news-listing p-0">
                        <div class="text-black row">
                            <div class="col-md-12 col-xl-4 news-image-sect"> <img
                                    src="{{ asset($newsDetail->getThumb()) }}" alt="{{ $newsDetail->title }}"></div>
                            <div class="col-sm-12 col-xl-8 news-body">
                               
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
                    @endforeach                    
                </div>
                 @endif   
            </div>
        </div>


@endsection


