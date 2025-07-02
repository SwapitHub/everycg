<?php
$router->group(['prefix' => 'user-orders'], function ($router) {
    $router->get('/', 'UserOrdersController@index')->name('admin_uorders.index');
    $router->get('create', 'UserOrdersController@create')->name('admin_uorders.create');
    $router->post('/create', 'UserOrdersController@postCreate')->name('admin_uorders.create');
    $router->get('/edit/{id}', 'UserOrdersController@edit')->name('admin_uorders.edit');
    $router->post('/edit/{id}', 'UserOrdersController@postEdit')->name('admin_uorders.edit');
    $router->post('/delete', 'UserOrdersController@deleteList')->name('admin_uorders.delete');
});