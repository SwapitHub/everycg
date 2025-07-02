@extends($templatePath.'.shop_layout')

@section('main')

    <div class="col-md-10 contact-col width_int">
            <div class="banner-sect-category"
                    style="background: url('{{ asset($pageData->image) }}');">
                    <div class="breadcrumb"><a href="{{ route('home') }}">Home </a> <span> <i class="fa fa-angle-right"></i></span>
                        {{ $pageData->title }}</div>
                    <h1 class="cat-title">{{ $pageData->title }}</h1>
                   <p class="content-category"> {!! sc_html_render($pageData->content) !!} </p>
                </div>
            <div class="main-contact-inner form-common-main form-common-main_data"
                style="background: url(images/workd-map.png);">
                <h2 class="contact-title">Contact Us</h2>  
                <form class="Contact_form" action="{{ route('contact.post') }}" method="post">
                     
                    <div class="mb-3 form_contact {{ $errors->has('name') ? ' has-error' : '' }}">
                        <input type="text" placeholder="Name" class="form-control {{ ($errors->has('name'))?"input-error":"" }}"" value="{{ old('name') }}" name="name" required>
                         @if ($errors->has('name'))
                            <span class="help-block">
                                {{ $errors->first('name') }}
                            </span>
                        @endif
                        </div>
                    <div class="mb-3 form_contact {{ $errors->has('title') ? ' has-error' : '' }}">
                        
                        <input type="text" placeholder="Title" class="form-control {{ ($errors->has('title'))?"input-error":"" }}" value="{{ old('title') }}" name="title" required>
                            @if ($errors->has('title'))
                                <span class="help-block">
                                    {{ $errors->first('title') }}
                                </span>
                            @endif
                    </div>
                    <div class="mb-3 form_contact {{ $errors->has('email') ? ' has-error' : '' }}">
                        
                        <input type="email"  placeholder="Email" class="form-control {{ ($errors->has('email'))?"input-error":"" }}" value="{{ old('email') }}" name="email" required>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                    <div class="mb-3 form_contact {{ $errors->has('phone') ? ' has-error' : '' }}">
                        <input type="tel"  name="phone"  placeholder="Phone Number" class="form-control no-arrow {{ ($errors->has('phone'))?"input-error":"" }}" value="{{ old('phone') }}" required>
                      @if ($errors->has('phone'))
                            <span class="help-block">
                                {{ $errors->first('phone') }}
                            </span>
                        @endif
                    </div>
                    <div class="mb-3 massage_content {{ $errors->has('content') ? ' has-error' : '' }}">
                        
                        <textarea required placeholder="Content.." class="form-control {{ ($errors->has('content'))?"input-error":"" }}" name="content">{{ old('content') }}</textarea>
                        @if ($errors->has('content'))
                            <span class="help-block">
                                {{ $errors->first('content') }}
                            </span>
                         @endif
                     </div>
                      {{ csrf_field() }}
                    <button class="btn btn-primary" type="submit">Submit</button>
                    
                </form>
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
