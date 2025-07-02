<?php
$router->group(['prefix' => 'cancel-plan'], function ($router) {
    $router->get('/', 'CancelPlanController@index')->name('admin_cancel_plan.index');
    $router->get('create', 'CancelPlanController@create')->name('admin_cancel_plan.create');
    $router->post('/create', 'CancelPlanController@postCreate')->name('admin_cancel_plan.create');
    $router->get('/cancel/{id}', 'CancelPlanController@edit')->name('admin_cancel_plan.edit');
    
    $router->post('/delete', 'CancelPlanController@deleteList')->name('admin_cancel_plan.delete');
});