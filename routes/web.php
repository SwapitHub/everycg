<?php
	// use Illuminate\Support\Facades\DB;
	/*
		|--------------------------------------------------------------------------
		| Web Routes
		|--------------------------------------------------------------------------
		|
		| Here is where you can register web routes for your application. These
		| routes are loaded by the RouteServiceProvider within a group which
		| contains the "web" middleware group. Now create something great!
		|
	*/
	
	/*
		Home
	*/
	Route::get('/test',function(){
	Artisan::call('migrate');
	   return 'ok';	
	});
	
	
	Route::get('/', 'ShopFront@index')->name('home');
	Route::get('index.html', 'ShopFront@index');  
	Route::post('stripe', 'ShopCart@stripePost')->name('stripe.post');
	Route::get('stripe', 'ShopCart@stripeGet');  
	Route::get('faq', 'ContentFront@getFaq')->name('faq');
	Route::get('vendor_register', 'ContentFront@vendorRegister')->name('vendor.register');
	Route::post('vendor_create', 'ContentFront@createVendor')->name('vendor.create');
	Route::get('vendor_login', 'ContentFront@loginVendor')->name('vendor.login');
	Route::get('connected_account', 'ShopCart@createConnected')->name('create.connected');
	Route::get('fund_transfer', 'ShopCart@fundTransfer')->name('fund.transfer');
	Route::get('verify-email/{id}', 'ContentFront@verifyMail')->name('mail.verify');
	Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
	Route::get('downloadcounter/{id}/{email}', 'ShopFront@DownloadCounter')->name('file.downloadcnt');
	Route::get('/landing', 'ShopFront@landing')->name('landing');
	// Route::post('/emailsubs', 'ShopFront@emailsubs')->name('emailsubs');
	
	// demo page for testing
	Route::get('/demo','ShopFront@demo')->name('demo');
	
	//Logged in users/seller cannot access or send requests these pages
	Route::group(['middleware' => 'seller_guest'], function() {
		
		Route::get('register_message', 'SellerAccount@RegisterMsg');
		
		Route::get('seller_register', 'SellerAuth\RegisterController@showRegistrationForm');
		Route::post('seller_register', 'SellerAuth\RegisterController@register');
		Route::get('seller_login', 'SellerAuth\LoginController@showLoginForm')->name('seller.login');
		//Route::post('seller_login', 'SellerAuth\LoginController@login');
		Route::post('/seller_login', [
		'uses'          => 'SellerAuth\LoginController@login',
		'middleware'    => 'checkstatus',
		]);
		
		//Password reset routes
		Route::get('seller_password/reset', 'SellerAuth\ForgotPasswordController@showLinkRequestForm');
		Route::post('seller_password/email', 'SellerAuth\ForgotPasswordController@sendResetLinkEmail');
		Route::get('seller_password/reset/{token}', 'SellerAuth\ResetPasswordController@showResetForm');
		Route::post('seller_password/reset', 'SellerAuth\ResetPasswordController@reset');
		Route::get('seller/account/verify/{token}', 'SellerAccount@verifyAccount')->name('seller.verify');
		
	});
	
	//Only logged in sellers can access or send requests to these pages
	Route::group(['middleware' => 'seller_auth'], function(){
		
		Route::post('seller_logout', 'SellerAuth\LoginController@logout');
		Route::get('seller_home', 'SellerAccount@index');
		Route::get('earning_history', 'SellerAccount@earnHistory')->name('earning.history');
		Route::post('generate_link', 'SellerAccount@generateLink')->name('affiliate.link');
		Route::get('seller-profile', 'SellerAccount@getProfile')->name('seller.profile');  
		Route::post('seller-profile', 'SellerAccount@postProfile')->name('seller.postprofile');  
		
	});
	
	Route::get('subsciption-plans', 'Subscribe@index');
	
	
	$suffix = sc_config('SUFFIX_URL')??'';
	
	/*
		Auth
	*/
	require_once 'component/auth.php';
	
	
	/*
		Member
	*/
	require_once 'component/member.php';
	
	/*
		Cart
	*/
	require_once 'component/cart.php';
	
	/*
		Category
	*/
	require_once 'component/category.php';
	
	/*
		Brand
	*/
	require_once 'component/brand.php';
	
	/*
		Vendor
	*/
	require_once 'component/vendor.php';
	
	/*
		Product
	*/
	require_once 'component/product.php';
	
	/*
		Content
	*/
	require_once 'component/content.php';
	
	//Language
	Route::get('locale/{code}', function ($code) {
		session(['locale' => $code]);
		return back();
	})->name('locale');
	
	//Currency
	Route::get('currency/{code}', function ($code) {
		session(['currency' => $code]);
		return back();
	});
	
	
	
	//Process click banner
	Route::get('/banner/{id}', 'ShopFront@clickBanner')
	->name('banner.click');    
	
	
	//--Please keep 2 lines route (pages + pageNotFound) at the bottom
	Route::get('/{alias}'.$suffix, 'ContentFront@pages')->name('pages');
	// Route::fallback('ShopFront@pageNotFound')->name('pageNotFound'); //Make sure before using this route. There will be disadvantages when detecting 404 errors for static files like images, scripts ..
	//--end keep
	
	//=======End Front
	
	
