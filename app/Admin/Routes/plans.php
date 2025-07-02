<?php
$router->group(['prefix' => 'plans'], function ($router) {
    $router->get('/', 'PlanController@index')->name('admin_plan.index');
    $router->get('create', 'PlanController@create')->name('admin_plan.create');
    $router->post('/create', 'PlanController@postCreate')->name('admin_plan.create');
    $router->get('/edit/{id}', 'PlanController@edit')->name('admin_plan.edit');
    $router->post('/edit/{id}', 'PlanController@postEdit')->name('admin_plan.edit');
    $router->post('/delete', 'PlanController@deleteList')->name('admin_plan.delete');
});