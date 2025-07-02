<?php
$router->group(['prefix' => 'marketplace'], function ($router) {
    $router->get('/', 'MarketplaceController@index')->name('admin_marketplace.index');
    $router->get('create', 'MarketplaceController@create')->name('admin_marketplace.create');
    $router->post('/create', 'MarketplaceController@postCreate')->name('admin_marketplace.create');
    $router->get('/edit/{id}', 'MarketplaceController@edit')->name('admin_marketplace.edit');
    $router->post('/edit/{id}', 'MarketplaceController@postEdit')->name('admin_marketplace.edit');
    $router->post('/delete', 'MarketplaceController@deleteList')->name('admin_marketplace.delete');
});