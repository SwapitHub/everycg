<?php
$router->group(['prefix' => 'product'], function ($router) {
    $router->get('/', 'ShopProductController@index')->name('admin_product.index');
    $router->get('create', 'ShopProductController@create')->name('admin_product.create');
    $router->post('/create', 'ShopProductController@postCreate')->name('admin_product.create');
    $router->get('/edit/{id}', 'ShopProductController@edit')->name('admin_product.edit');
    $router->post('/edit/{id}', 'ShopProductController@postEdit')->name('admin_product.edit');
    $router->post('/delete', 'ShopProductController@deleteList')->name('admin_product.delete');
    $router->get('/import', 'ShopProductController@import')->name('admin_product.import');
    $router->post('/import', 'ShopProductController@postImport')->name('admin_product.import');

    Route::post('image/upload/store','ImageUploadController@fileStore')->name('admin_product_img.store');
    Route::post('image/delete','ImageUploadController@fileDestroy')->name('admin_product_img.delete');
     $router->get('/duplicate/{id}', 'ShopProductController@duplicate')->name('admin_product.duplicate');
     $router->post('/sub-cat', 'ShopProductController@getSubcat')->name('admin_product.subcat');
     $router->post('/related-product', 'ShopProductController@getRelated')->name('admin_product.related');

     $router->post('/getsubcat', 'ShopProductController@getSubMultiple')->name('admin_product.getsubcat');
});
