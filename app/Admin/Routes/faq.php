<?php
$router->group(['prefix' => 'faq'], function ($router) {
    $router->get('/', 'FaqController@index')->name('admin_faq.index');
    $router->get('create', 'FaqController@create')->name('admin_faq.create');
    $router->post('/create', 'FaqController@postCreate')->name('admin_faq.create');
    $router->get('/edit/{id}', 'FaqController@edit')->name('admin_faq.edit');
    $router->post('/edit/{id}', 'FaqController@postEdit')->name('admin_faq.edit');
    $router->post('/delete', 'FaqController@deleteList')->name('admin_faq.delete');
});