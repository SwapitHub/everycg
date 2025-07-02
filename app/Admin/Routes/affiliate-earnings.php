<?php
$router->group(['prefix' => 'affiliate-earnings'], function ($router) {
    $router->get('/', 'AffEarnController@index')->name('admin_affearning.index');
    
});