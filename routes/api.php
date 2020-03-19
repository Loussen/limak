<?php

use Illuminate\Http\Request;

Route::post('/login', 'Api\AuthController@login');
Route::post('/register', 'Api\AuthController@register');
Route::post('/change-password', 'Api\UserController@changePassword');
Route::post('/activation', 'Api\AuthController@activation');
Route::get('/currencies', 'Api\InfoController@getCurrenciesData');
Route::get('/countries', 'Api\InfoController@getCountriesData');
Route::get('/countries2', 'Api\InfoController@getCountriesData2');
Route::get('/contacts', 'Api\InfoController@getContactsData');
Route::get('/user-rules', 'Api\InfoController@getUserRulesData');
Route::get('/questions', 'Api\InfoController@getQuestionsData');
Route::get('/page', 'Api\InfoController@getPage');
Route::get('/news', 'Api\InfoController@getNews');



Route::group(['middleware' => 'api'], function() {
    Route::get('/main', 'Api\UserController@main');
    Route::get('/pay', 'Api\UserController@pay');
    Route::get('/status', 'Api\UserController@status');
    Route::post('/addBasket', 'Api\UserController@addBasket');
    Route::post('/addInvoice', 'Api\UserController@addInvoice');
    Route::post('/order', 'Api\UserController@order');
    Route::post('/orderBalance', 'Api\UserController@orderBalance');
    Route::post('/courier', 'Api\UserController@courier');
    Route::get('/{action}', 'Api\UserController@getUserData');
    Route::post('/confirmation', 'Api\UserController@confirmation');
    Route::post('/confirmation-code', 'Api\UserController@confirmationCode');
    Route::post('/cancel-courier', 'Api\UserController@cancelOnlyCourier');
    Route::post('/payInvoice', 'Api\UserController@payInvoice');
    Route::post('/payOnlyCourier', 'Api\UserController@payOnlyCourier');
    Route::post('/addClient', 'Api\UserController@addClient');
    Route::post('/editClient', 'Api\UserController@editClient');
    Route::get('/get-invoice/{id}', 'Api\UserController@getInvoice');

    Route::post('/post-settings-data-profile', 'Api\UserController@storeProfile');
    Route::post('/post-settings-data-passport', 'Api\UserController@storePassport');
    Route::post('/post-settings-data-password', 'Api\UserController@storePassword');


    /*Route::get('/main', 'Api\UserController@main');
    Route::get('/lastBalanceOperation', 'Api\UserController@lastBalanceOperation');
    Route::get('/basket', 'Api\UserController@basket');

    Route::get('/orders', 'Api\UserController@orders');*/
});


