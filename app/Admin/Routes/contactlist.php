<?php
$router->group(['prefix' => 'mailer/contact'], function ($router) {
    $router->get('/', 'ContactListController@index')->name('mailer_contact.index');
    $router->get('create', 'ContactListController@create')->name('mailer_contact.create');
    $router->post('/create', 'ContactListController@postCreate')->name('mailer_contact.create');
    $router->get('/edit/{id}', 'ContactListController@edit')->name('mailer_contact.edit');
    $router->post('/edit/{id}', 'ContactListController@postEdit')->name('mailer_contact.edit');
    $router->post('/delete', 'ContactListController@deleteList')->name('mailer_contact.delete');

    // import contact in db using excel sheet 
    $router->get('/import', 'ContactListController@importIndex')->name('mailer_contact.import');
    $router->get('/import/create', 'ContactListController@importCreate')->name('mailer_contact.import.create');
    $router->post('/import/create', 'ContactListController@postImportCreate')->name('mailer_contact.import.create');
    $router->get('/import/edit/{id}', 'ContactListController@ImportEdit')->name('mailer_contact.import.edit');
    $router->post('/import/edit/{id}', 'ContactListController@postImportEdit')->name('mailer_contact.import.edit');
    $router->post('/import/delete', 'ContactListController@deleteContact')->name('mailer_contact.import.delete');



    $router->post('/import', 'ContactListController@importContacts')->name('import.contact');

});
