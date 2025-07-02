<?php
$router->group(['prefix' => 'transfer'], function ($router) {
    $router->get('/', 'TransferController@index')->name('admin_transfer.index');
    $router->get('create', 'TransferController@create')->name('admin_transfer.create');
    $router->post('/create', 'TransferController@postCreate')->name('admin_transfer.create');
    $router->get('/edit/{id}', 'TransferController@edit')->name('admin_transfer.edit');
    $router->post('/edit/{id}', 'TransferController@postEdit')->name('admin_transfer.edit');
    $router->post('/delete', 'TransferController@deleteList')->name('admin_transfer.delete');
});