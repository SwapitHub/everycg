@extends($templatePath.'.shop_layout')

@section('center')
<style>
	.shop-now-btn {
    margin-top: 35px;
}
</style>
<section class="banner-sections">
	<div class="banner-slide">
		@if (!empty($slider))
		<?php $isl=0;
			foreach($slider as $slide) { ?>
			<div class="slider-outer" style="background-image:url(./images/bg.jpg);">
				<div class="slider-inner-sec">
					<div class="container">
						<div class="banner-row">
							
							<div class="text-side-sec">
								<div class="txttt-boxss">
									{!! sc_html_render($slide->html) !!}
								</div>
								<div class="btn-sec-boxx">
									<a href="{{ $slide->url }}" class="shop-orange-btn">{{ $slide->url_text }}</a>
								</div>
							</div>
							
							<div class="imag-side-sec">
								<div class="img-boxss">
									<img src="{{ asset($slide->image) }}" alt="{{ $slide->image }}">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		@endif
	</div>
</section>
<section class="skew-slidess-n">
	<div class="custmm-container-slides">
		<div class="skeww-slides">
			@foreach ($catlist1 as  $key => $cat)
			<div class="skeww-slides-outer">
				<a href="{{ $cat->getUrl() }}">
					<div class="img-skew-wrapper">
						<img src="<?php if($cat->getThumb()) {?>{{ asset($cat->getThumb()) }}<?php } else {?>{{ asset('/images/0_skew-imag.png') }}<?php } ?>" alt="{{ $cat->name }}">
					</div>
					<div class="skew-images-captonns">
						<h4>{{ $cat->name }}</h4>
						<h6>category</h6>
					</div>
				</a>
			</div>
			@endforeach	
		</div>
	</div>
</section>
<section class="icons-globlss">
	<div class="container">
		<div class="global-logos-row">
			<?php $realistic = App\Models\Widget::getSinglewidget('realistic-section-home'); 
			echo $realistic->description; ?>
		</div>
	</div>
</section>
<section class="product-list-sectionsss">
	<div class="container">
		
		<div class="heading-sectionss">
			<h2>Featured</h2>
		</div>
		
		<div class="row ">
			@foreach ($products_new as  $key => $product_new)
			
			<?php if($product_new->name == NULL){ continue; } ?>
			<?php  $pname = App\Models\ProductFiles::getFileExt($product_new['id']); ?>  
			<div class="col-6 col-md-3 col-xl-3 with_hund_pt mt-3">
				<div class="com_cover_div">
					<a href="{{ $product_new->getUrl() }}">
						<div class="text-black featured_Products">
							<div class="featured_Products_image card ">
								<img src="{{ asset($product_new->getThumb()) }}" alt="{{ $product_new->name }}">
								<div class="Shop_now">
									
									<span class="pname">@php
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
							@endphp</h5>
							<!---@if($product_new->stock == 0)
							 <div class="list-stock">
								 Out of stock
							</div>
							@endif--->
							 
							<div class="pro-details">
								<p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
								<div class="pro-content" style="display:none">
									@php
									echo substr($product_new->content, 0, 60).'...';
								@endphp                                  </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endforeach                                       
		</div>
	</div>
</section>

<section class="product-list-sectionsss">
	<div class="container">
		
		<div class="heading-sectionss">
			<h2>Most Popular</h2>
		</div>
		
		
		<div class="row ">
			
			<div class="col-6 col-md-3 col-xl-3 with_hund_pt mt-3">
				<div class="com_cover_div">
					<a href="https://everycg.com/product/office-chair">
						<div class="text-black featured_Products">
							<div class="featured_Products_image card ">
								<img src="{{ asset('/data/product/38_Office Chair 1/Chair.webp') }}" alt="Office Chair">
								<div class="Shop_now">
									
									<span class="pname">FBX | MA | OBJ | USD</span> <span>  <span class="sc-new-price">$5</span> </span>
									
								</div>
								
							</div>
						</div>
					</a>
					<div class="card-body">
						<div class="text-center">
							<h5 class="card-title">Office Chair</h5>
							<div class="pro-details">
								<p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
								<div class="pro-content" style="display:none">
								EveryCG 3D Models&nbsp;- This 3D Model was modeled in great ...                                     </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-6 col-md-3 col-xl-3 with_hund_pt mt-3">
				<div class="com_cover_div">
					<a href="https://everycg.com/product/human">
						<div class="text-black featured_Products">
							<div class="featured_Products_image card ">
								<img src="{{ asset('/data/product/37_Human_Sofia/2.jpg') }}" alt="Human">
								<div class="Shop_now">
									
									<span class="pname">FBX | MA | OBJ | USD</span> <span>  <span class="sc-new-price">$5</span> </span>
									
								</div>
								
							</div>
						</div>
					</a>
					<div class="card-body">
						<div class="text-center">
							<h5 class="card-title">Human</h5>
							<div class="pro-details">
								<p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
								<div class="pro-content" style="display: none;">
								EveryCG 3D Models&nbsp;- This 3D Model was modeled in great ...                                     </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-6 col-md-3 col-xl-3 with_hund_pt mt-3">
				<div class="com_cover_div">
					<a href="https://everycg.com/product/sofa-3-seater">
						<div class="text-black featured_Products">
							<div class="featured_Products_image card ">
								<img src="{{ asset('/data/product/36_Sofa/Sofa.webp') }}" alt="Sofa - 3 seater">
								<div class="Shop_now">
									
									<span class="pname">FBX | MA | OBJ | USD</span> <span>  <span class="sc-new-price">$12</span> </span>
									
								</div>
								
							</div>
						</div>
					</a>
					<div class="card-body">
						<div class="text-center">
							<h5 class="card-title">Sofa - 3 seater</h5>
							<div class="pro-details">
								<p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
								<div class="pro-content" style="display:none">
								EveryCG 3D Models&nbsp;- This 3D Model was modeled in great ...                                     </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-6 col-md-3 col-xl-3 with_hund_pt mt-3">
				<div class="com_cover_div">
					<a href="https://everycg.com/product/kitchen-knife-set">
						<div class="text-black featured_Products">
							<div class="featured_Products_image card ">
								<img src="{{ asset('/data/product/35_Kitchen_knife_Set/Knife.webp') }}" alt="Kitchen Knife Set">
								<div class="Shop_now">
									
									<span class="pname">FBX | MA | OBJ | USD</span> <span>  <span class="sc-new-price">$5</span> </span>
									
								</div>
								
							</div>
						</div>
					</a>
					<div class="card-body">
						<div class="text-center">
							<h5 class="card-title">Kitchen Knife Set</h5>
							<div class="pro-details">
								<p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
								<div class="pro-content" style="display:none">
								EveryCG 3D Models&nbsp;- This 3D Model was modeled in great ...                                     </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
		</div>
		<div class="row">
			<div class="col-md-12 col-12 shop-now-btn">
				<a href="{{url('/product')}}" class="shop-orange-btn">Shop Now</a>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript" src="scripts/jquery.cookies.2.2.0.min.js"></script> 

<section class="threee-D-programss">
	<div class="container">
		<?php $Dprogram = App\Models\Widget::getSinglewidget('3d-programs-format'); 
		echo $Dprogram->description; ?>
		
	</div>
</section>


<div class="news-lteter-pop-up" style="display:none">
	
	<div class="pop-wrapss">
		<div class="close-pop" onclick="ClosePopup()" ><i class="fa fa-times" aria-hidden="true"></i></div>
		<div class="rows">
			
			<div class="col-md-6">
				
				<img src="https://everycg.com/data/product/40_Grass_1/Grass.webp" class="w-100" />
			</div>
			
			<div class="col-md-6">
				<?php $newsletter = App\Models\Widget::getSinglewidget('newsletter-popup'); 
		echo $newsletter->description; ?>
				
				
				<!-- Begin Mailchimp Signup Form -->
				<link href="//cdn-images.mailchimp.com/embedcode/classic-071822.css" rel="stylesheet" type="text/css">
				<style type="text/css">
					#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
				</style>
				<div id="mc_embed_signup">
					<form action="https://everycg.us21.list-manage.com/subscribe/post?u=a7872ce4951b1d652428f6a9f&amp;id=8c60ea4c4c&amp;f_id=0031f4e1f0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						<div id="mc_embed_signup_scroll">
							<div class="mc-field-group">
								<input type="email" value="" name="EMAIL" placeholder="Email Address" class="required email" id="mce-EMAIL" required>
							</div>
							<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_a7872ce4951b1d652428f6a9f_8c60ea4c4c" tabindex="-1" value=""></div>
							<div class="optionalParent">
								<div class="clear foot">
									<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"  >
								</div>
							</div>
							<div id="mce-responses" class="clear foot">
								<div class="response" id="mce-error-response" style="display:none"></div>
								<div class="response" id="mce-success-response" style="display:none"></div>
							</div>  
						</div>
					</form>
				</div>
				<!--script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script-->
				<script src="https://everycg.com/js/chimpmail.js"></script>
				<script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';fnames[5]='BIRTHDAY';ftypes[5]='birthday';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
				<!--End mc_embed_signup-->
				<!--form action="{{ route('emailsubs') }}" method="post" class="form subscribe" id="popsubs">
					<div class="foot-n-newslett">
					@csrf
					<div class="control-nnns">
					<input name="subscribe_email" type="email" id="newsletter" placeholder="Enter your email address" required="">
					</div>
					<div class="actions-nnn">
					<button class="new-submittt"  title="Subscribe" type="submit">
					<span>Subscribe</span>
					</button>
					</div>
					@if ($errors->has('subscribe_email'))
					<span class="help-block">
					{{ $errors->first('subscribe_email') }}
					</span>
					@endif
					</div>
				</form--->
			</div>
		</div>
	</div>
	
</div>


@endsection


@push('styles')
@endpush

@push('scripts')
<script>
	$(document).ready(function(){	
		<?php $user = Auth::user();
			if($user) {  ?>        
			
			var username = $("form#vendor-login input[name='username']").val();
			var password = $("form#vendor-login input[name='password']").val();
			$.ajax({
				url:'{{ route('admin.login') }}',
				type:'POST',
				data:{username:username,password:password,"_token": "{{ csrf_token() }}"},
				success: function(data){
					console.log('success');
				}
			});          
		<?php } ?>
		
		$('.shop-page').addClass('home-page');
		
		$('.slider').slick({
			dots: true,
			arrows: true,
			autoplay: true,
			autoplaySpeed: 5000,
			infinite: true,
			speed: 500,
			cssEase: 'linear',
			slidesToShow: 1,
			slidesToScroll: 1
			
		});
	});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js" integrity="sha512-efUTj3HdSPwWJ9gjfGR71X9cvsrthIA78/Fvd/IN+fttQVy7XWkOAXb295j8B3cmm/kFKVxjiNYzKw9IQJHIuQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
	$(document).ready(function(){
	$(".news-lteter-pop-up").css('display','none');
		// $(".news-lteter-pop-up").show();
		let cookie_consent = getCookie("user_cookie_consent");
		console.log(cookie_consent);
		if(cookie_consent != ""){
			$(".news-lteter-pop-up").hide();
			}else{
			setTimeout(function(){
				$(".news-lteter-pop-up").show();
			},4000);
			
		} 
	});
	
	function ClosePopup()
	{
		$(".news-lteter-pop-up").hide();
	}
	$(window).on('load',function(){
		$(".top-footerr").attr('display','block');
	});
	
	
	// Create cookie
	function setCookie(cname, cvalue, exdays) {
		const d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		let expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}
	// Delete cookie
	function deleteCookie(cname) {
		const d = new Date();
		d.setTime(d.getTime() + (24*60*60*1000));
		let expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=;" + expires + ";path=/";
	}
	// Read cookie
	function getCookie(cname) {
		let name = cname + "=";
		let decodedCookie = decodeURIComponent(document.cookie);
		let ca = decodedCookie.split(';');
		for(let i = 0; i <ca.length; i++) {
			let c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	
	
	
	
	// Set cookie consent
	function acceptCookieConsent(){
		setCookie('user_cookie_consent', 1, 30);
		$(".news-lteter-pop-up").hide();
	}
	
	
	
	// $(document).ready(function(){		
		// $("#popsform").submit(function(e){
			// e.preventDefault();
			// var data = new FormData(this);
			// $.ajax({
				// type: $(this).attr('method'),
				// url: $(this).attr('action'),
				// data: data,
				// cache: false,
				// contentType: false,
				// processData: false,	
				// success:function(response)
				// {
					// var json_data = JSON.parse(response);
					// console.log(json_data);
					// if(json_data.res =="success"){
						// $.notify("Sucessfully Subscribed", "success");
						// acceptCookieConsent();
						// ClosePopup();
						
					// }
					// else if(json_data.res =="error")
					// {
						// $.notify("already subscribed, try another email", "info");	
					// }
				// },
				// error: function(response)
				// {
					// console.log(response);
				// }
				
			// })
			
		// })
	// });
	
	
	
	
	
</script>

@endpush
