<?php
$prefixProduct = sc_config('PREFIX_PRODUCT')??'product';

Route::group(['prefix' => $prefixProduct], function ($router) use($suffix) {
    $router->get('/', 'ShopFront@allProducts')->name('product.all');
    $router->post('/info', 'ShopFront@productInfo')
        ->name('product.info');
    $router->get('/{alias}', 'ShopFront@productDetail')
        ->name('product.detail');
    Route::get('tag/{alias}', 'ShopFront@tagProducts')->name('tag.products'); 
});