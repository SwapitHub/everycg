@if (sc_config('SITE_STATUS') == 'off')
  @include($templatePath . '.maintenance')
  @php
    exit();
  @endphp
@endif

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>	
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $description??sc_store('description') }}">
    <meta name="keyword" content="{{ $keyword??sc_store('keyword') }}">
    <title>{{$title??sc_store('title')}}</title>
    <meta property="og:image" content="{{ !empty($og_image)?asset($og_image):asset('images/org.jpg') }}" />
    <meta property="og:url" content="{{ \Request::fullUrl() }}" />
    <meta property="og:type" content="Website" />
    <meta property="og:title" content="{{ $title??sc_store('title') }}" />
    <meta property="og:description" content="{{ $description??sc_store('description') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
      .social-icons ul.foot-social-lissttt br {display: none;}
.social-icons ul.foot-social-lissttt li img:first-child {margin-left: 0;}
.social-icons ul.foot-social-lissttt li img {width: 28px;height: 28px;margin-left: 5px;}
.social-icons ul.foot-social-lissttt li{display: inline-block; margin-top: 23px;padding-right: 12px;}
.social-icons ul.foot-social-lissttt li img:hover {
    opacity: 0.9;
    margin-top: -5px;
	transition: all 0.4s ease-in-out;
}
.list-stock {
	background-color: #f7931d;
	color: #fff;
	font-weight: 500;
	text-align: center;
	padding: 4px 10px;
	width: auto;
	font-size: 14px;
	margin-bottom: 10px;
	display: inline-block;
}
    </style>

<!--Module meta -->
  @isset ($blocksContent['meta'])
      @foreach ( $blocksContent['meta']  as $layout)
        @php
          $arrPage = explode(',', $layout->page)
        @endphp
        @if ($layout->page == '*' ||  (isset($layout_page) && in_array($layout_page, $arrPage)))
          @if ($layout->type =='html')
            {!! $layout->text !!}
          @endif
        @endif
      @endforeach
  @endisset
<!--//Module meta -->

<!-- css default for item s-cart -->
@include('common.css')
<!--//end css defaut -->
    
  
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ asset('/css/slick.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    <!-- <link href="{{ asset($templateFile.'/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset($templateFile.'/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{ asset($templateFile.'/css/prettyPhoto.css')}}" rel="stylesheet">
    <link href="{{ asset($templateFile.'/css/animate.css')}}" rel="stylesheet">
    <link href="{{ asset($templateFile.'/css/main.css')}}" rel="stylesheet">
    <link href="{{ asset($templateFile.'/css/responsive.css')}}" rel="stylesheet">    
    <link rel="shortcut icon" href="{{ asset($templateFile.'/images/ico/favicon.ico')}}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset($templateFile.'/images/ico/apple-touch-icon-144-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset($templateFile.'/images/ico/apple-touch-icon-114-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset($templateFile.'/images/ico/apple-touch-icon-72-precomposed.png')}}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset($templateFile.'/images/ico/apple-touch-icon-57-precomposed.png')}}"> -->
    <link rel="shortcut icon" href="{{ asset(sc_store('favicon_icon'))}}">



  <!--Module header -->
  @isset ($blocksContent['header'])
      @foreach ( $blocksContent['header']  as $layout)
      @php
        $arrPage = explode(',', $layout->page)
      @endphp
        @if ($layout->page == '*' ||  (isset($layout_page) && in_array($layout_page, $arrPage)))
          @if ($layout->type =='html')
            {!! $layout->text !!}
          @endif
        @endif
      @endforeach
  @endisset
<!--//Module header -->

<!--//Google Search Console Start -->
<meta name="google-site-verification" content="ywRs0WB0Ky2hfvHsC5w3cAc0PXW8tLFHaH4UsraDzcA" />
<!--//Google Search Console End -->

<!--//Google Tag Manager -->

<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5JDCPGP');</script>

<!-- End Google Tag Manager -->

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-270046606-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-270046606-1');
</script>

<!--//Bing Webmaster Start -->

<meta name="msvalidate.01" content="BBBC2CCDF808115110DE53DF268527AB" />

<!--//Bing Webmaster End -->

</head>
<!--//head-->
<body>

@include($templatePath.'.header')
@section('main')
@include($templatePath.'.center')
@show
@yield('content')

@include($templatePath.'.footer')

<script src="{{ asset($templateFile.'/js/jquery.js')}}"></script>
<script src="{{ asset($templateFile.'/js/jquery-ui.min.js')}}"></script>
<script src="{{ asset('/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('/js/slick.min.js') }}"></script>
<script>
  $(document).ready(function() {
     


    var newClass = window.location.href;
newClass = newClass.substring(newClass.lastIndexOf('/')+1);
$('body').addClass(newClass);
    $( ".page-item .page-link[rel*='next']" ).html('<i class="fa fa-angle-right"></i>');
    $( ".page-item .page-link[rel*='prev']" ).html('<i class="fa fa-angle-left"></i>');

     $('.open_tagle').click(function(){
        $('.togial_menu_div').addClass('toggle-active');
    });
     $('.close_tagle').click(function(){
        $('.togial_menu_div').removeClass('toggle-active');
    });
     $('.search_div .dextop_hide').click(function(){
        $('.search_div .search_form_mant').slideToggle();
     });
     
  var pathname = window.location.href;
   pathname = pathname.replace(/\/$/, "");
  
  $('.nav-menu.leftSideBar li a[href="'+pathname+'"]').parent().addClass('navbar__link--active');
  $('.nav-menu.leftSideBar li a[href="'+pathname+'"]').parents('.nav-item.category').addClass('navbar__link--active');

  $('.nav-item.has-submenu .show-menu').click(function(){
    if($(this).hasClass('fa-angle-down')){
      $(this).removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
      $(this).removeClass('fa-angle-up').addClass('fa-angle-down');
    }
    $(this).parents('li').siblings().find('ul.submenu').hide();
    $(this).parents('li').siblings().find('.show-menu').removeClass('fa-angle-up').addClass('fa-angle-down');
    $(this).next('ul').slideToggle();
  });

   $('.pro-details .show-detail').click(function(){
    if($(this).find('i.fa').hasClass('fa-angle-down')){
      $(this).find('i.fa').removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
      $(this).find('i.fa').removeClass('fa-angle-up').addClass('fa-angle-down');
    }    
    // $(this).parents('.pro-details').find('.pro-content').slideToggle();
  });

   $('.category.navbar__link--active .show-menu').removeClass('fa-angle-down').addClass('fa-angle-up');
   $('.category.navbar__link--active ul.submenu').show();

   $("form#vendor-login").appendTo(".footer-sec-divide.user_area ul");
   $("li.affiliate-menu").appendTo(".footer-sec-divide.user_area ul");
   $("li.affiliate-menu").click(function(){
      $('li.affiliate-menu ul.submenu').slideToggle();
   });
}); 

</script>

<!-- <script src="{{ asset($templateFile.'/js/bootstrap.min.js')}}"></script>
<script src="{{ asset($templateFile.'/js/jquery.scrollUp.min.js')}}"></script>
<script src="{{ asset($templateFile.'/js/jquery.prettyPhoto.js')}}"></script>
<script src="{{ asset($templateFile.'/js/main.js')}}"></script> -->


@stack('scripts')

<!-- js default for item s-cart -->
@include('common.js')
<!--//end js defaut -->

   <!--Module bottom -->
   @isset ($blocksContent['bottom'])
       @foreach ( $blocksContent['bottom']  as $layout)
         @php
           $arrPage = explode(',', $layout->page)
         @endphp
         @if ($layout->page == '*' ||  (isset($layout_page) && in_array($layout_page, $arrPage)))
           @if ($layout->type =='html')
             {!! $layout->text !!}
           @elseif($layout->type =='view')
             @if (view()->exists('block.'.$layout->text))
              @include('block.'.$layout->text)
             @endif
           @elseif($layout->type =='module')
             {!! sc_block_render($layout->text) !!}
           @endif
         @endif
       @endforeach
   @endisset
 <!--//Module bottom -->

<!-- Google Tag Manager (noscript) -->

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5JDCPGP"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<!-- End Google Tag Manager (noscript) →



</body>
</html>
