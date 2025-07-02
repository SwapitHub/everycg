<?php
$router->group(['prefix' => 'vendor-agreement'], function ($router) {
    $router->get('/', 'VendorAgreementController@index')->name('admin_vagreement.index');
    $router->get('create', 'VendorAgreementController@create')->name('admin_vagreement.create');
    $router->post('/create', 'VendorAgreementController@postCreate')->name('admin_vagreement.create');
    $router->get('/edit/{id}', 'VendorAgreementController@edit')->name('admin_vagreement.edit');
    $router->post('/edit/{id}', 'VendorAgreementController@postEdit')->name('admin_vagreement.edit');
    $router->post('/delete', 'VendorAgreementController@deleteList')->name('admin_vagreement.delete');
});