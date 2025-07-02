<?php $user = Auth::user();  
$catList = App\Models\ShopCategory::where('status',1)->where('top',1)->get(); ?>

<div class="head-top-bar">
	<div class="container">      
        <div class="tbar-txtt"><?php $noticebar = App\Models\Widget::getSinglewidget('notice-bar'); 
		echo $noticebar->description; ?></div>
	</div>
</div>
<div class="header_div black-headerr "> 
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="collapse navbar-collapse container-fluid" id="navbarSupportedContent">
				<div class="col-md-2 d-flex nab_left">
					<a href="{{ route('home') }}" class="navbar-brand col-md-3"><img src="{{ asset(sc_store('logo')) }}" alt="logo"></a>                 
					
				</div>
				<div class="col-md-10 d-flex nab_right">
					
					<div class="search_div">
						<button class="tab_btn search_name dextop_hide">
							<i class="fa fa-search"></i>
						</button>
						<div class="search_form_mant">
                            <form class="search_form" method="get" action="{{ route('search') }}"><input type="text" required="" name="keyword" placeholder="Search" value="<?php if(isset($_GET['keyword'])) echo $_GET['keyword']; ?>"><button class="btn search_name" type="submit"><i class="fa fa-search"></i></button></form>
						</div>
					</div>
					
					
					
					<div class=" cart new-cartt">
						<a href="{{ route('cart') }}" class='cart_btn_dt'>
							<img src="{{ asset('images/cartIcon-01.png') }}" alt="dsfs" />
							<p class='queant'>{{ Cart::instance('default')->count() }}</p>
						</a>
					</div>
					
					<style>
						.cart.new-cartt img {
						width: 100%;
						max-width: 22px;
						}
					</style>
					
					<!-- 
                        <div class=" cart contact-bttnn">
						<a href="{{ route('contact') }}" class="avtarr_btn_dt">
						<img src="{{ asset('/images/avatarnewww.png') }}" alt="dsfs">
						</a>
                        </div>
					-->
					<div class="mobile_hide">
						@guest
						<div class="register_loginout_login custom-for-desk">
							<div class="dropdown">
								<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2">
									<i class="fa fa-user-circle-o"></i>
								</button>
								<ul class="register_or_login dropdown-menu dropdownMenu2">
									<li class="contact-bttn-headdd">
										<a class="nav-link" href="{{ route('contact') }}">Contact Us</a> 
									</li>      
									
									<li class="login-bttn-headdd">
										<a class="nav-link" href="{{ route('login') }}">Login</a>
									</li>                                   
									<li class="register-bttn-headdd">
										<a class="nav-link" href="{{ route('register') }}">Register</a> 
									</li>                                   
									
									
								</ul>
							</div>
						</div>
						@else
						<div class="register_or_login login_out">
							<div class="dropdown">
								<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1">
									<i class="fa fa-user-circle-o"></i>
								</button>
								<ul class="dropdown-menu dropdownMenu1">
									<li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="/sc_admin">dashboard</a> 
									</li> 
									<li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="/sc_admin/vendor_order">my sales</a> 
									</li>
									<li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="/sc_admin/user-orders">orders & downloads</a> 
									</li>                
									<!--  <li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="{{ route('member.download') }}">purchases & downloads</a> 
									</li> -->
									<!--  <li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="{{ route('member.change_address') }}">address</a> 
									</li> -->
									<li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="/sc_admin/account-setting">account details</a> 
									</li>
									<li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="/sc_admin/auth/setting">user profile</a> 
									</li>
									<li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="/sc_admin/agreement">Payments & agreements</a> 
									</li>
									<li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="{{ route('contact') }}">Contact Us</a> 
										
									</li>
									<li class="hr">
										<hr>
									</li>
									
									
									<li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="/sc_admin/product/create">publish new model</a> 
									</li>
									<li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="/sc_admin/product">published models</a> 
									</li>
									<li class="nav-item has-submenu category">
										<a class="nav-link outer-cat" href="{{ url('/logout') }}" >logout</a> 
									</li>
								</ul>
							</div>
						</div>
						@endguest
					</div>
				</div>
			</div>
		</nav>
	</div>
	
</div>

<div class="cstm-container-fluidss">
	<div class="container">
		
		@guest
		<div class="col-md-2 leftSideBar_mane_dv togial_menu_div">      
			@else
			<div class="col-md-2 leftSideBar_mane_dv togial_menu_div user-log-menu">           
				@endguest
				<button class="open_tagle"><i class="fa fa-bars"></i></button>
				<div class="nav-menu leftSideBar">
					<div>
						<span class="logo_imag_togel"> <a href="{{ route('home') }}" class="navbar-brand col-md-3"><img  src="{{ asset(sc_store('logo')) }}" alt="logo" /></a></span><button class="close_tagle"><i class="fa fa-close"></i></button>
						@include('block.categories')
						
					</div>
				</div>
			</div> 
			
			
			@include('block.ncategories')
		</div>
	</div>
	
	
	
