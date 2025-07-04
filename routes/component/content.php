<?php
$prefixSearch = sc_config('PREFIX_SEARCH')??'search';
$prefixContact = sc_config('PREFIX_CONTACT')??'contact';
$prefixNews = sc_config('PREFIX_NEWS')??'news';

Route::get('/'.$prefixSearch, 'ShopFront@search')
->name('search');
Route::post('/subscribe', 'ContentFront@emailSubscribe')
->name('subscribe');
Route::post('/emailsubs', 'ContentFront@emailsubs')->name('emailsubs');
Route::get('/'.$prefixContact, 'ContentFront@getContact')
->name('contact');
Route::post('/contact', 'ContentFront@postContact')
->name('contact.post');
Route::get('/'.$prefixNews, 'ContentFront@news')
->name('news');
Route::get('/'.$prefixNews.'/{alias}', 'ContentFront@newsDetail')
->name('news.detail');
