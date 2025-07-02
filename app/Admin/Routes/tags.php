<?php
$router->group(['prefix' => 'tags'], function ($router) {
    $router->get('/', 'TagController@index')->name('admin_tag.index');
    $router->get('create', 'TagController@create')->name('admin_tag.create');
    $router->post('/create', 'TagController@postCreate')->name('admin_tag.create');
    $router->get('/edit/{id}', 'TagController@edit')->name('admin_tag.edit');
    $router->post('/edit/{id}', 'TagController@postEdit')->name('admin_tag.edit');
    $router->post('/delete', 'TagController@deleteList')->name('admin_tag.delete');
});
