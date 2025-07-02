<?php
$router->group(['prefix' => 'mailer/contact'], function ($router) {
    $router->get('/', 'ContactListController@index')->name('mailer_contact.index');
    $router->get('create', 'ContactListController@create')->name('mailer_contact.create');
    $router->post('/create', 'ContactListController@postCreate')->name('mailer_contact.create');
    $router->get('/edit/{id}', 'ContactListController@edit')->name('mailer_contact.edit');
    $router->post('/edit/{id}', 'ContactListController@postEdit')->name('mailer_contact.edit');
    $router->post('/delete', 'ContactListController@deleteList')->name('mailer_contact.delete');
});
