<?php
$router->group(['prefix' => 'download-count'], function ($router) {
    $router->get('/', 'DownloadController@index')->name('admin_downloadcnt.index');
    $router->post('/delete', 'DownloadController@deleteList')->name('admin_downloadcnt.delete');
});
