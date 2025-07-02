<?php
$router->group(['prefix' => 'user-plans'], function ($router) {
    $router->get('/', 'UserPlanController@index')->name('admin_userplan.index');
    $router->get('create', 'UserPlanController@create')->name('admin_userplan.create');
    $router->post('/create', 'UserPlanController@postCreate')->name('admin_userplan.create');
    $router->get('/edit/{id}', 'UserPlanController@edit')->name('admin_userplan.edit');
    $router->post('/edit/{id}', 'UserPlanController@postEdit')->name('admin_userplan.edit');
    $router->post('/delete', 'UserPlanController@deleteList')->name('admin_userplan.delete');
});