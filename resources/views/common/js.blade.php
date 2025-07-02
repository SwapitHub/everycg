<script type="text/javascript">
  function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
  }
  $('#shipping').change(function(){
    $('#total').html(formatNumber(parseInt({{ Cart::subtotal() }})+ parseInt($('#shipping').val())));
  });
</script>
<script src="https://everycg.com/templates/default/js/jquery.js"></script>
<script src="https://everycg.com/templates/default/js/jquery-ui.min.js"></script>
<script src="https://everycg.com/js/bootstrap.bundle.min.js"></script> 
<script src="https://everycg.com/js/slick.min.js"></script> 
<script>
    $('.banner-slide').slick({
        dots: false,
        arrows: true,
        autoplay: false,
        autoplaySpeed: 2000,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1
    });

    $('.skeww-slides').slick({
  dots: false,
  arrows: true,
  infinite: true,
  speed: 300,
  autoplay: false,
  autoplaySpeed: 2000,
  pauseOnFocus:false,
  pauseOnHover:false,
  swipe:true,
  accessibility:true,
  slidesToShow: 7,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 6,
        slidesToScroll: 1,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});


</script>


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
 /*
   $('.nav-item.has-submenu .show-menu').click(function(){
     if($(this).hasClass('fa-angle-down')){
       $(this).removeClass('fa-angle-down').addClass('fa-angle-up');
     } else {
       $(this).removeClass('fa-angle-up').addClass('fa-angle-down');
     }
     //$(this).parents('li').siblings().find('ul.submenu').hide();
     //$(this).parents('li').siblings().find('.show-menu').removeClass('fa-angle-up').addClass('fa-angle-down');
     //$(this).next('ul').slideToggle();
   });*/
 
    $('.pro-details .show-detail').click(function(){
     if($(this).find('i.fa').hasClass('fa-angle-down')){
       $(this).find('i.fa').removeClass('fa-angle-down').addClass('fa-angle-up');
     } else {
       $(this).find('i.fa').removeClass('fa-angle-up').addClass('fa-angle-down');
     }    
     $(this).parents('.pro-details').find('.pro-content').slideToggle();
   });
 
    $('.category.navbar__link--active .show-menu').removeClass('fa-angle-down').addClass('fa-angle-up');
    $('.category.navbar__link--active ul.submenu').show();
 
    $("form#vendor-login").appendTo(".footer-sec-divide.user_area ul");
    $("li.affiliate-menu").appendTo(".footer-sec-divide.user_area ul");
    $("li.affiliate-menu").click(function(){
       $('li.affiliate-menu ul.submenu').slideToggle();
    });
	
	$( "#dropdownMenuButton2" ).click(function() {
		$( ".dropdown-menu.dropdownMenu2" ).toggle();
	});
	$( "#dropdownMenuButton1" ).click(function() {
		$( ".dropdown-menu.dropdownMenu1" ).toggle();
	});
 }); 
 
</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.7/bootstrap-notify.min.js"></script>

<!--process cart-->
<script type="text/javascript">
  function addToCartAjax(id,instance = null){
    $.ajax({
        url: "{{ route('cart.add_ajax') }}",
        type: "POST",
        dataType: "JSON",
        data: {"id": id,"instance":instance, "_token":"{{ csrf_token() }}"},
        async: false,
        success: function(data){
          // console.log(data);
            error= parseInt(data.error);
            if(error ==0)
            {
              setTimeout(function () {
                if(data.instance =='default'){
                  $('.sc-cart').html(data.count_cart);
                }else{
                  $('.sc-'+data.instance).html(data.count_cart);
                }
              }, 1000);

                $.notify({
                  icon: 'glyphicon glyphicon-star',
                  message: data.msg
                },{
                  type: 'success'
                });
            }else{
              if(data.redirect){
                window.location.replace(data.redirect);
                return;
              }
              $.notify({
              icon: 'glyphicon glyphicon-warning-sign',
                message: data.msg
              },{
                type: 'danger'
              });
            }

            }
    });
  }
</script>
<!--//end cart -->


<!--message-->
@if(Session::has('success'))
<script type="text/javascript">
    $.notify({
      icon: 'glyphicon glyphicon-star',
      message: "{!! Session::get('success') !!}"
    },{
      type: 'success'
    });
</script>
@endif

@if(Session::has('error'))
<script type="text/javascript">
    $.notify({
    icon: 'glyphicon glyphicon-warning-sign',
      message: "{!! Session::get('error') !!}"
    },{
      type: 'danger'
    });
</script>
@endif

@if(Session::has('warning'))
<script type="text/javascript">
    $.notify({
    icon: 'glyphicon glyphicon-warning-sign',
      message: "{!! Session::get('warning') !!}"
    },{
      type: 'warning'
    });
</script>
@endif
<!--//message-->

<div id="sc-loading">
  <div class="sc-overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw "></i></div>
</div>