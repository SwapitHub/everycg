<?php
$router->group(['prefix' => 'contact_us'], function ($router) {
    $router->get('/', 'ContactController@index')->name('admin_contact_us.index');
    $router->get('create', 'ContactController@create')->name('admin_contact_us.create');
    $router->post('/create', 'ContactController@postCreate')->name('admin_contact_us.create');
    $router->get('/edit/{id}', 'ContactController@edit')->name('admin_contact_us.edit');
    $router->post('/edit/{id}', 'ContactController@postEdit')->name('admin_contact_us.edit');
    $router->post('/delete', 'ContactController@deleteList')->name('admin_contact_us.delete');
});
