<?php
use App\Message;
Route::group(['namespace' => 'Cron'], function () {
    Route::get('/getCurrency', 'CronController@currency');
    Route::get('/smsSend', 'CronController@smsSend');
    Route::get('/notificationSend', 'CronController@notificationSend');
    Route::get('/sendByDay/{day}', 'CronController@sendByDay');
    Route::get('/emailSend', 'CronController@emailSend');
    Route::get('/test/index', 'TestController@index');
    Route::get('/vipexStatus', 'CronController@vipexStatus');
    Route::get('/test/fakeUsers', 'TestController@fakeUsers');
    Route::get('/test/update-package', 'TestController@updatePackageId');
    Route::get('/depo', 'CronController@depo');
    Route::get('/depoMinusFine', 'CronController@depoMinusFine');
    Route::get('/corporate', 'CronController@corporate');


    Route::get('/usa-packages', 'UsaController@getPackages');


});