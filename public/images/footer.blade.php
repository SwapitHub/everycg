<!--Footer-->

<!--Module top footer -->
@isset ($blocksContent['footer'])
@foreach ( $blocksContent['footer']  as $layout)
@php
$arrPage = explode(',', $layout->page)
@endphp
@if ($layout->page == '*' ||  (isset($layout_page) && in_array($layout_page, $arrPage)))
@if ($layout->type =='html')
{!! $layout->text !!}
@elseif($layout->type =='view')
@if (view()->exists('blockView.'.$layout->text))
@include('blockView.'.$layout->text)
@endif
@elseif($layout->type =='module')
{!! sc_block_render($layout->text) !!}
@endif
@endif
@endforeach
@endisset










<!--//Module top footer -->
<div class="top-footerr">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h4>Every cg Newsletter</h4>
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industryremaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, </p>
				
				
				<form action="{{ route('subscribe') }}" method="post" class="form subscribe">
					<div class="foot-n-newslett">
						@csrf
						<div class="control-nnns">
							<input name="subscribe_email" type="email" id="newsletter" placeholder="Enter your email address" required="">
						</div>
						<div class="actions-nnn">
							<button class="new-submittt" title="Subscribe" type="submit">
								<span>Subscribe</span>
							</button>
						</div>
						@if ($errors->has('subscribe_email'))
						<span class="help-block">
							{{ $errors->first('subscribe_email') }}
						</span>
						@endif
					</div>
				</form>
			</div>
			<div class="col-md-6">
				<?php $shop = App\Models\Widget::getSinglewidget('second-footer'); 
				echo $shop->description; ?>
				
			</div>
		</div>
	</div>
</div>

<div class="m-0 footer">
	<div class="container">
		<div class="row">
			
			<div class="col-lg-3 col-md-6  col-sm-6">
                <div class="footer-sec-divide why_brand">
                    <a class="footer_logo" href="{{ route('home') }}"><img src="{{  asset(sc_store('logo')) }}" alt="logo"></a>
					<ul>
						
						<li><a class="footer_atn hover" href="/"> <span>{{ sc_store('address') }} </span></a></li>
						<li><a class="footer_atn hover" href="/"><span>{{ sc_store('time_active') }}</span></a></li>
					</ul>       
				</div>
			</div>
			
			<div class="col-lg-3 col-md-6  col-sm-6">
                <div class="footer-sec-divide why_brand">
					<?php $shop = App\Models\Widget::getSinglewidget('third-footer'); 
					echo $shop->description; ?>
					
				</ul>        
			</div>
		</div>
		
		<div class="col-lg-3 col-md-6  col-sm-6">
			<div class="footer-sec-divide why_brand">
                <?php $support = App\Models\Widget::getSinglewidget('fourth-footer'); 
				echo $support->description; ?>      
			</div>
		</div>
		
		<div class="col-lg-3 col-md-6  col-sm-6">
			<div class="footer-sec-divide why_brand">				
				<?php $support = App\Models\Widget::getSinglewidget('fifth-footer'); 
				echo $support->description; ?>
			</div>
		</div>
		
		
	</div>
	<div class="copyRighrt">
		<hr>
		<p>{{ sc_store('copyright') }}</p>
	</div>
</div>
</div>

<?php $user = Auth::user(); ?>
@if (Auth::check())
<form id="vendor-login" action="{{ route('admin.login') }}" method="post">
	@csrf
	<input type="hidden" name="username" value="{{ $user->username }}">
	<input type="hidden" name="password" value="{{ $user->userpwd }}">
	<li><a class="footer_atn hover" href="{{ route('admin.login') }}" onclick="event.preventDefault();  document.getElementById('vendor-login').submit();">Publish Your Product</a></li>
</form>
@endif

<?php /* <li class="affiliate-menu" style="cursor: pointer;">
	<a class="footer_atn hover">Affiliate Marketing <i class="fa fa-angle-down show-menu"></i></a>  
	
	<ul class="submenu" style="display:none; padding-left: 15px !important;">
	@if (Auth::guard('web_seller')->guest())
	<li class="nav-item  category">
	<a class="footer_atn hover" href="{{ url('/seller_login') }}">Login</a>
	</li>
	<li class="nav-item  category">
	<a class="footer_atn hover" href="{{ url('/seller_register') }}">Register</a>
	</li>
	@else
	<li class="nav-item category">
	<a class="footer_atn hover" href="{{ url('/seller_home') }}">Affiliate Link</a>
	</li>
	<li class="nav-item category">
	<a class="footer_atn hover" href="{{ url('/earning_history') }}">Earnings History</a>
	</li>
	<li class="nav-item category">
	<a class="footer_atn hover" href="{{ url('/seller-profile') }}">Edit Profile</a>
	</li>
	<li class="nav-item category">
	<a  class="footer_atn hover" href="{{ url('/seller_logout') }}" onclick="event.preventDefault(); document.getElementById('slogout-form').submit();">  Logout   </a>
	</li>
	<form id="slogout-form" action="{{ url('/seller_logout') }}" method="POST" style="display: none;">
	{{ csrf_field() }}
	</form>
	@endif
	
	</ul>
</li>*/ ?>
</body>  



</html>