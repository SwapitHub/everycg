@extends($templatePath.'.shop_layout')

@section('main')
<div class="page-wrappsss my-4">
 <div class="container">
            <div class="row g-0">
                 <h1 class="title text-center">{{ $title }}</h1>
        {!! sc_html_render($page->content) !!}
            </div>
        </div>
</div>
@endsection

@section('breadcrumb')
    <div class="breadcrumbs">
        <ol class="breadcrumb">
          <li><a href="{{ route('home') }}">{{ trans('front.home') }}</a></li>
          <li class="active">{{ $title }}</li>
        </ol>
      </div>
@endsection
