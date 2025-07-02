<?php
$router->group(['prefix' => 'license'], function ($router) {
    $router->get('/', 'LicenseController@index')->name('admin_license.index');
    $router->get('create', 'LicenseController@create')->name('admin_license.create');
    $router->post('/create', 'LicenseController@postCreate')->name('admin_license.create');
    $router->get('/edit/{id}', 'LicenseController@edit')->name('admin_license.edit');
    $router->post('/edit/{id}', 'LicenseController@postEdit')->name('admin_license.edit');
    $router->post('/delete', 'LicenseController@deleteList')->name('admin_license.delete');
});
