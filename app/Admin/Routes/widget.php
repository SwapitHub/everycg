<?php
$router->group(['prefix' => 'widget'], function ($router) {
    $router->get('/', 'WidgetController@index')->name('admin_widget.index');
    $router->get('create', 'WidgetController@create')->name('admin_widget.create');
    $router->post('/create', 'WidgetController@postCreate')->name('admin_widget.create');
    $router->get('/edit/{id}', 'WidgetController@edit')->name('admin_widget.edit');
    $router->post('/edit/{id}', 'WidgetController@postEdit')->name('admin_widget.edit');
    $router->post('/delete', 'WidgetController@deleteList')->name('admin_widget.delete');
});