<?php
   $router->group(['prefix' => 'customer-emails'], function ($router) {
    $router->get('/', 'CustomerEmailController@index')->name('admin_customeremail.index');
    $router->get('create', 'CustomerEmailController@create')->name('admin_customeremail.create');
    $router->post('/create', 'CustomerEmailController@postCreate')->name('admin_customeremail.create');
    $router->get('/edit/{id}', 'CustomerEmailController@edit')->name('admin_customeremail.edit');
    $router->post('/edit/{id}', 'CustomerEmailController@postEdit')->name('admin_customeremail.edit');
    $router->post('/delete', 'CustomerEmailController@deleteList')->name('admin_customeremail.delete');
    $router->get('/maillist/{id}', 'CustomerEmailController@maillist')->name('admin_customeremail.maillist');
    $router->post('/sendmail/{id}', 'CustomerEmailController@sendmail')->name('admin_customeremail.sendmail');
});

 
