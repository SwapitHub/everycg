@extends($templatePath.'.shop_layout')

@section('center')
<div class="container width_int product-detail-page">
	<div class="row g-0 product-single-main">
		<div class="col-lg-9 col-md-7">
			<div class="d-flex flex-column justify-content-center product_slider_theme">
				<div class="thumbnail_images">
					<div class="carousel-root">
						<div class="" style="width: 100%;">
							<div class="slider-wrapper axis-horizontal">
								<ul class="slider animated"
								style="transform: translate3d(0px, 0px, 0px); transition-duration: 350ms;">
									<div>
										<li class="slide">
											<img src="{{ asset($product->getImage()) }}"
											alt="{{ $product->alias }}">
										</li>
									</div>
									@if ($product->images->count())
									@foreach ($product->images as $key=>$image)
									<div>
										<li class="slide"><img
											src="{{ asset($image->getImage()) }}"
										alt="{{ $image->getImage() }}"></li>
									</div>
									@endforeach
									@endif 
								</ul>
							</div>
						</div>
						<div class="">
							<div class="thumbs-wrapper axis-vertical">
								<ul class="thumbs animated slider-nav-thumbnails"
								style="transform: translate3d(0px, 0px, 0px); transition-duration: 350ms;">
									<div>
										<li class="thumb">
											<img   src="{{ asset($product->getImage()) }}" alt="{{ $product->alias }}">
										</li>
									</div>
									@if ($product->images->count())
									@foreach ($product->images as $key=>$image)
									<div>
										<li class="thumb">
											<img src="{{ asset($image->getImage()) }}"
											alt="<?php echo substr($image->getImage(), strrpos($image->getImage(), '/') + 1); ?>">
										</li>
									</div>
									@endforeach
									@endif
									
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3  col-md-5">
			<div class="p-3 right-side product-info-right">
				<h1 class="more-heading">{{ $product->name }}</h1>
				<div class="formats">
					<h4>Formats</h4>
					<p> <?php echo $pname = App\Models\ProductFiles::getFileExt($product['id']);  ?></p>
				</div> 
				<!--div class="formats">
					<h4>Availability</h4>
					<p> <?= ($product->stock ==0)?'out of stock': 'ins tock'; ?></p>
				</div--> 
				<div class="formats">
					<h4>Product Code</h4>
					<p> <?= $product->sku ?></p>
				</div> 
				<div class="formats">
					<h4>Tags</h4>
					<p> <?= $product->tags;?></p>
				</div> 
				
				<div class="pro-price">
					<h4>Price</h4>
					<p> {!! $product->showPrice() !!}</p>
				</div>
				@if($product->stock !=0)
				
				<div class="buttons d-flex flex-row gap-3 button-n-share">
					<form id="buy_block" action="{{ route('cart.add') }}" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="product_id" id="product-detail-id" value="{{ $product->id }}" />                         
						<button  type="submit" class="btn btn-dark cart">Add to
						Cart</button>
					</form>
					<!-- <a class="share-icooo"><i class="fa fa-share-alt" aria-hidden="true"></i></a> -->
				</div>
				@else
					<!--for rollback old code uncommant the out of stock code and remove code below the ofs -->
				
					<!--<div class="list-stock">
					  Out of stock
					</div>-->
				<div class="buttons d-flex flex-row gap-3 button-n-share">
					<form id="buy_block" action="{{ route('cart.add') }}" method="post">
						{{ csrf_field() }}
						<input type="hidden" name="product_id" id="product-detail-id" value="{{ $product->id }}" />                         
						<button  type="submit" class="btn btn-dark cart">Add to
						Cart</button>
					</form>
				</div> 
				@endif
				<?php
				$actual_link = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
				?>
				<div class="share-iconss-n">
				<?php
				$currenturl = url()->full();	
				?>
					<h4>Share link</h4>
					<a class="share-listt" href="javascript:void(0)" onclick="window.open('https://facebook.com/share.php?u=<?= $currenturl ?>&amp;t=PAGE_TITLE_HERE', '_blank', 'top=150,left=400,width=450,height=550')" ><i class="fa fa-facebook" aria-hidden="true"></i></a>
					<a class="share-listt" href="javascript:void(0)" onclick="window.open('https://twitter.com/intent/tweet?text=PAGE_TITLE_HERE&amp;url=<?= $currenturl ?>&amp;via=USERNAME_HERE', '_blank', 'top=150,left=400,width=550,height=350')" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
					<a class="share-listt" href="javascript:void(0)" onclick="window.open('https://plus.google.com/share?url=<?= $currenturl ?>', '_blank', 'top=150,left=400,width=450,height=550')" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
					<a class="share-listt" href="javascript:void(0)" target="_blank"><i class="fa fa-rss" aria-hidden="true"></i></a>
				</div>
				
				<?php if($publisher->id!=1) { ?>
					<div class="publisher-main">
						<h1 class="more-heading">Publisher</h1>
						<div class="publisher-inner">
							<img src="<?php if($publisher->avatar) echo asset($publisher->avatar); else echo asset('/images/User-Profile.png'); ?>">
							<h3>{{ $publisher->name }}</h3>
							<a href="tel:{{ $publisher->contact }}">Contact Publisher</a>
						</div>
					</div>
				<?php } ?>
				
				<?php if(!empty($plan)){ ?>
					<div class="file-downloads">
						<div class="subscribe-download">
							<a href="javascript:void(0);">
								Download  <i class="fa fa-solid fa-caret-down" style="color: #c5c5c5;"></i>
							</a> 
							<ul class="subscribe-download-list" style="display:none;">  
								<!-- User image -->
								<?php  
									foreach ($files as $file) {  
									$filename = substr($file->name, strrpos($file->name, '/') + 1); ?>
									<li class="user-header">
										<a data-src="{{ $file->id }}" href="{{ asset($file->name) }}"><?php echo substr($filename, 13); ?></a>
									</li>  
								<?php } ?>                             
								
							</ul>
						</div>
					</div>
				<?php } ?>
				
				
				@if (count($tags) > 0)     
				<!--div class="tag_btn"><span>Tags:-</span>
					@foreach ($tags as $tag)
					<a href="{{ route('tag.products',['alias'=>$tag->alias]) }}">  {{ $tag->name }} </a>
					@endforeach       
				</div-->
				@endif   
				
			</div>
		</div>
	</div>
	
	<div class="row product-info">
		<h4 class="more-heading">More Details</h4>
		<div class="product-cont"> 
            {!! sc_html_render($product->content) !!}
		</div>
	</div>
	@if ($related->count())
    <div class="related_news row">
		<h3 class="more-heading">Related Product</h3>
		@foreach ($related as  $key => $product_rel)
		<?php  $pname = App\Models\ProductFiles::getFileExt($product_rel['id']); ?>
		<div class="col-6 col-md-4 col-xl-3 with_hund_pt mt-3">
			<div class="com_cover_div">
				<a href="{{ $product_rel->getUrl() }}">
					<div class="text-black featured_Products"> 
						<div class="featured_Products_image card ">
							<img src="{{ asset($product_rel->getThumb()) }}" alt="{{ $product_rel->name }}" />
							<div class="Shop_now">
								
								<span class="pname">@php
									echo strlen($pname) > 20 ? substr($pname,0,25)."..." : $pname;
								@endphp</span> <span>  {!! $product_rel->showPrice() !!} </span>
								
							</div>  
							
						</div>
					</div>
				</a>
				<div class="card-body">
					<div class="text-center">
						<h5 class="card-title">@php
							echo strlen($product_rel->name) > 20 ? substr($product_rel->name,0,20).".." : $product_rel->name;
						@endphp</h5>
						<div class="pro-details">
							<p class="show-detail">Details <i class="fa fa-angle-down"></i></p>
							<div class="pro-content" style="display:none">
								@php
								echo substr($product_rel->content, 0, 60).'...';
								@endphp
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>   
		@endforeach
	</div>
	@endif
</div>


@endsection

@section('breadcrumb')
@endsection

@push('styles')

@endpush

@push('scripts')
<script type="text/javascript">
	$('.subscribe-download a').click(function(){
        $(this).siblings('ul.subscribe-download-list').slideToggle();
	})
    $('.user-header a').click(function(e){
		e.preventDefault();
		var file = $(this).data('src');
		var src = $(this).attr('href');
		$.ajax({
			url:'{{ route('member.download_count') }}',
			type:'POST',
			data:{file:file,"_token": "{{ csrf_token() }}"},
			success: function(data){               
				window.location.href = src;
			}
		});
		//return false;
	});
	$('.slider').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		fade: true,
		asNavFor: '.slider-nav-thumbnails',
		accesibility: false,
		draggable: false,
		swipe: false,
		touchMove: false
	});
	
	$('.slider-nav-thumbnails').slick({
		slidesToShow: 5,
		slidesToScroll: 1,
		asNavFor: '.slider',
		dots: false,
		arrows: true,
		focusOnSelect: true,
		focusOnSelect: true,
		infinite: false,
		responsive: [
        {
            breakpoint: 1080 ,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
			}
		},
        {
            breakpoint: 560,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 1
			}
		},
		]
		
	});
	
	// Remove active class from all thumbnail slides
	$('.slider-nav-thumbnails .slick-slide').removeClass('slick-active');
	
	// Set active class to first thumbnail slides
	$('.slider-nav-thumbnails .slick-slide').eq(0).addClass('slick-active');
	
	// On before slide change match active thumbnail to current slide
	$('.slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
		var mySlideNumber = nextSlide;
		$('.slider-nav-thumbnails .slick-slide').removeClass('slick-active');
		$('.slider-nav-thumbnails .slick-slide').eq(mySlideNumber).addClass('slick-active');
	});
	
	$('.sc-product-group').click(function(event) {
		if($(this).hasClass('active')){
			return;
		}
		$('.sc-product-group').removeClass('active');
		$(this).addClass('active');
		var id = $(this).data("id");
		$.ajax({
			url:'{{ route("product.info") }}',
			type:'POST',
			dataType:'json',
			data:{id:id,"_token": "{{ csrf_token() }}"},
			beforeSend: function(){
				$('#loading').show();
			},
			success: function(data){
				//console.log(data);
				$('#product-detail-cart-group').show();
				$('#product-detail-name').html(data.name);
				$('#product-detail-model').html(data.sku);
				$('#product-detail-price').html(data.showPrice);
				$('#product-detail-brand').html(data.brand_name);
				$('#product-detail-image').html(data.showImages);
				$('#product-detail-available').html(data.availability);
				$('#product-detail-id').val(data.id);
				$('#product-detail-image').carousel();
				$('#loading').hide();
				window.history.pushState("", "", data.url);            
			}
		});
		
	});
	
</script>
@endpush
