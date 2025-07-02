<?php
$router->group(['prefix' => 'seller_user'], function ($router) {
    $router->get('/', 'SellerController@index')->name('admin_suser.index');
    $router->get('create', 'SellerController@create')->name('admin_suser.create');
    $router->post('/create', 'SellerController@postCreate')->name('admin_suser.create');
    $router->get('/edit/{id}', 'SellerController@edit')->name('admin_suser.edit');
    $router->post('/edit/{id}', 'SellerController@postEdit')->name('admin_suser.edit');
    $router->post('/delete', 'SellerController@deleteList')->name('admin_suser.delete');
});
