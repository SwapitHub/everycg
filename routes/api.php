<?php
	
	use Illuminate\Http\Request;
	
	/*
		|--------------------------------------------------------------------------
		| API Routes
		|--------------------------------------------------------------------------
		|
		| Here is where you can register API routes for your application. These
		| routes are loaded by the RouteServiceProvider within a group which
		| is assigned the "api" middleware group. Enjoy building your API!
		|
	*/
	// Route::middleware('auth:api')->get('/user', function (Request $request) {
	//     return $request->user();
	// });
	Route::post('login', 'Api\AuthController@login');
	Route::post('register', 'Api\AuthController@register');
	
	Route::middleware('auth:api')->get('/user', function (Request $request) {
		return $request->user();
	});
	Route::group(['middleware' => 'auth:api'], function(){
		Route::get('user-detail', 'Api\AuthController@userDetail');
		Route::post('user/update', 'Api\AuthController@updateUser');
		Route::get('orders', 'Api\AuthController@orderList'); 
		Route::get('orders/{id}', 'Api\AuthController@order');
		Route::get('user', 'Api\ShopCart@user');
		Route::post('add_cart', 'Api\ShopCart@addToCart');
		Route::get('cart', 'Api\ShopCart@getCart');
		Route::post('cart/delete/{id}', 'Api\ShopCart@deleteCartitem');
		Route::post('logout', 'Api\AuthController@logOut');
		Route::post('order_create', 'Api\ShopCart@createOrder');
		Route::get('cart_count', 'Api\ShopCart@cartCount');
		Route::post('cart_update', 'Api\ShopCart@updateCart');
		Route::post('order_complete', 'Api\ShopCart@completeOrder');
		Route::post('create_product', 'Api\ProductController@postCreate');
		Route::get('product/edit/{id}', 'Api\ProductController@edit');
		Route::get('vendor-products', 'Api\ProductController@products');
		Route::get('account-setting', 'Api\VendorController@getAccount');
		Route::post('account-setting', 'Api\VendorController@setting');
		Route::post('verify-email', 'Api\VendorController@sendmail');
		Route::get('agreement', 'Api\VendorController@GetAgreement');
		Route::post('agreement', 'Api\VendorController@Agreement');
	});
	
	Route::get('categories', 'ApiController@allCategory'); 
	Route::get('categories/{id}', 'ApiController@categoryDetail');
	Route::get('products', 'ApiController@allProduct');
	Route::get('productsnew', 'ApiController@allProductNew');
	Route::get('products/{id}', 'ApiController@productDetail');
	Route::get('productsnew/{id}', 'ApiController@productDetailNew');
	Route::get('category-products/{id}', 'ApiController@productToCategory');
	Route::get('category_pro/{alias}', 'ApiController@productToCategoryNew');
	Route::get('site-info', 'ApiController@siteInfo');
	Route::get('home-page', 'ApiController@homeInfo');  
	Route::get('slider', 'ApiController@getSlider');
	Route::post('create_order', 'ApiController@createOrder'); 
	Route::post('stripe', 'ApiController@stripePost');
	Route::post('complete_order', 'ApiController@completeOrder');
	Route::get('products/tag/{id}', 'ApiController@tagProducts');  
	Route::get('products/license/{id}', 'ApiController@licenseProducts');  
	Route::get('block_content', 'ApiController@blockContent');  
	Route::get('search/{id}', 'ApiController@search');  
	Route::post('contact', 'ApiController@postContact'); 
	Route::get('news', 'ApiController@getNews'); 
	Route::get('news/{id}', 'ApiController@newsdetail'); 
	Route::post('add-to-cart', 'ApiController@addToCart');
	Route::post('get_cart', 'ApiController@getCart');
	Route::post('update_cart', 'ApiController@updateCart');
	Route::post('remove_cart', 'ApiController@deleteCartitem');
	Route::get('countries', 'ApiController@getCounties');
	Route::post('cart_total', 'ApiController@cartCount');
	Route::get('get-page/{alias}', 'ApiController@getPage'); 
	Route::get('featured_products', 'ApiController@featuredProduct');
	Route::get('orders/{id}', 'ApiController@order');
	Route::get('month_year', 'ApiController@getYearMonth');
	Route::get('widget/{alias}', 'ApiController@getWidget');
	Route::get('faq', 'ApiController@getFaq');
	Route::get('tags', 'ApiController@getTags');
	Route::get('license', 'ApiController@getLicense');
	
	
	
