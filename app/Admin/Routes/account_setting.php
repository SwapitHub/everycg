<?php
$router->group(['prefix' => 'account-setting'], function ($router) {
    $router->get('/', 'AccountController@index')->name('admin_accsett.index');
    $router->get('create', 'AccountController@create')->name('admin_accsett.create');
    $router->post('/create', 'AccountController@postCreate')->name('admin_accsett.create');
    $router->get('/edit', 'AccountController@edit')->name('admin_accsett.edit');
    $router->post('/edit', 'AccountController@postEdit')->name('admin_accsett.edit');
    $router->post('/delete', 'AccountController@deleteList')->name('admin_accsett.delete');
    $router->post('/sendmail', 'AccountController@sendMail')->name('admin_accsett.mail');
});