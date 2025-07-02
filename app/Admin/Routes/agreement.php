<?php
$router->group(['prefix' => 'agreement'], function ($router) {
    $router->get('/', 'AgreementController@index')->name('admin_agreement.index');    
    $router->post('/create', 'AgreementController@postCreate')->name('admin_agreement.create');
    
});