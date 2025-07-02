<?php
$prefixMember = sc_config('PREFIX_MEMBER')??'member';

Route::group(['prefix' => $prefixMember, 'middleware' => 'auth'], function ($router) use($suffix){
    $prefixMemberOrderList = sc_config('PREFIX_MEMBER_ORDER_LIST')??'order-list';
    $prefixMemberChangePwd = sc_config('PREFIX_MEMBER_CHANGE_PWD')??'change-password';
    $prefixMemberChangeInfo = sc_config('PREFIX_MEMBER_CHANGE_INFO')??'change-infomation';

    $router->get('/', 'ShopAccount@index')->name('member.index');
    $router->get('/'.$prefixMemberOrderList, 'ShopAccount@orderList')
        ->name('member.order_list');  
    $router->get('/order/{id}', 'ShopAccount@orderDetail')
        ->name('member.order');
    $router->get('/'.$prefixMemberChangePwd, 'ShopAccount@changePassword')
        ->name('member.change_password');
    $router->post('/change_password', 'ShopAccount@postChangePassword')
        ->name('member.post_change_password');
    $router->get('/'.$prefixMemberChangeInfo, 'ShopAccount@changeInfomation')
        ->name('member.change_infomation');
    $router->post('/change_infomation', 'ShopAccount@postChangeInfomation')
        ->name('member.post_change_infomation');
    $router->get('/change-address', 'ShopAccount@changeAddress')
        ->name('member.change_address');
    $router->post('/changeAddress', 'ShopAccount@postchangeAddress')
        ->name('member.post_change_address');
        $router->get('/download', 'ShopAccount@getDownload')
        ->name('member.download');
    $router->post('/download_count', 'ShopAccount@downloadCount')
        ->name('member.download_count');
});