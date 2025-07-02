<?php
	Auth::routes();
	
	//--Auth
	Route::group(['namespace' => 'Auth', 'prefix' => 'member'], function ($router) {
		$router->get('/login', 'LoginController@showLoginForm')
        ->name('login');
		$router->post('/login', [
		'uses'          => 'LoginController@login',
		'middleware'    => 'is_verify_email',])->name('postLogin');
		$router->get('/register', 'LoginController@showRegisterForm')
        ->name('register');
		$router->post('/register', 'RegisterController@register')
        ->name('postRegister');
		$router->post('/logout', 'LoginController@logout')
        ->name('logout');
		$router->post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')
        ->name('password.email');
		$router->post('/password/reset', 'ResetPasswordController@reset');
		$router->get('/password/reset/{token}', 'ResetPasswordController@showResetForm')
        ->name('password.reset');
		$router->get('/forgot', 'ForgotPasswordController@showLinkRequestForm')
        ->name('forgot');
		$router->get('account/verify/{token}', 'LoginController@verifyAccount')->name('user.verify');
		$router->get('subsciption/{id}', 'SubscribeController@getPlan')->name('purchase-plan');
		$router->post('subscribe-pay', 'SubscribeController@subscribePay')->name('subscribe.post');
	});
//End Auth