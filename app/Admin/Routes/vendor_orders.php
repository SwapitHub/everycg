<?php
$router->group(['prefix' => 'vendor_order'], function ($router) {
    $router->get('/', 'VendorOrderController@index')->name('admin_vorder.index');
    $router->get('/detail/{id}', 'VendorOrderController@detail')->name('admin_vorder.detail');
    $router->get('create', 'VendorOrderController@create')->name('admin_vorder.create');
    $router->post('/create', 'VendorOrderController@postCreate')->name('admin_vorder.create');
    $router->post('/add_item', 'VendorOrderController@postAddItem')->name('admin_vorder.add_item');
    $router->post('/edit_item', 'VendorOrderController@postEditItem')->name('admin_vorder.edit_item');
    $router->post('/delete_item', 'VendorOrderController@postDeleteItem')->name('admin_vorder.delete_item');
    $router->post('/update', 'VendorOrderController@postOrderUpdate')->name('admin_vorder.update');
    $router->post('/delete', 'VendorOrderController@deleteList')->name('admin_vorder.delete');
    $router->get('/product_info', 'VendorOrderController@getInfoProduct')->name('admin_vorder.product_info');
    $router->get('/user_info', 'VendorOrderController@getInfoUser')->name('admin_vorder.user_info');
    $router->get('/export_detail', 'VendorOrderController@exportDetail')->name('admin_vorder.export_detail');

});
