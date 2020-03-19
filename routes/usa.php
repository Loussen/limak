<?php

Route::group(['middleware' => 'web'], function () {

    // These routes are require user to be log  ged in
    Route::group(['middleware' => 'auth:usa'], function () {

        // Constant subdomain
        Route::get('/', 'Us\InvoiceController@test');
        Route::get('/logout', 'Us\InvoiceController@logout');
        Route::get('/invoices/get/xml', 'Us\InvoiceController@xml');
        Route::get('/invoices/change-purchase/{purchase_no}', 'Us\InvoiceController@changePurchase');
        Route::get('/invoices/get/manifest', 'Us\InvoiceController@manifest');
        Route::get('/invoices/get/manifest2', 'Us\InvoiceController@manifest2');
        Route::get('/invoices/get/manifest2', 'Us\InvoiceController@manifest2');
        Route::post('/invoices/check-storage', 'Us\InvoiceController@addToOnTheWay');
        Route::post('/invoices/check-waiting', 'Us\InvoiceController@addToForeignStorage');
        Route::post('/invoices/check-road', 'Us\InvoiceController@addToStorage');
        Route::post('/invoices/check-native-storage', 'Us\InvoiceController@addToCompleted');
        Route::post('/invoices/change-status', 'Us\InvoiceController@changeStatus');
        Route::post('/invoices/addCustomStatus', 'Tr\InvoiceController@addCustomStatus');
        Route::post('/invoices/change-all-stauses', 'Us\InvoiceController@changeAllStatuses');
        Route::get('/invoices/change-vehicle', 'Us\InvoiceController@changeVehicle');
        Route::post('/invoices/send-invoiceless-message', 'Us\InvoiceController@sendInvoicelessMessage');
        Route::get('/invoices/waybill/{id}', 'Us\InvoiceController@waybillNew');
        Route::get('/invoices/waybillAll', 'Us\InvoiceController@waybillAll');
        Route::get('/invoices/waybillOld/{id}', 'Us\InvoiceController@waybill');

        Route::get('/invoices/get/index', 'Us\InvoiceController@test');
        Route::get('/invoices/get/stored', 'Us\InvoiceController@stored');
        Route::get('/print-hawb/{productId}', 'Us\ProductsController@printHawb')->name('tr_printHawb');
        Route::post('/api/invoice/set', 'Us\APIController@setInvoiceDataById')->name('tr_setInvoiceDataById');
        Route::get('/api/invoice/{id}', 'Us\APIController@getInvoiceDataById')->name('tr_getInvoiceDataById');
        Route::post('/invoices/change-status', 'Us\InvoiceController@changeStatus');
        Route::post('/invoices/change-status-back', 'Us\InvoiceController@changeStatusBack');
        Route::post('/invoices/send-invoiceless-message', 'Us\InvoiceController@sendInvoicelessMessage');
        Route::get('/invoices/change-purchase/{purchase_no}', 'Us\InvoiceController@changePurchase');
        Route::post('/invoices', 'Us\InvoiceController@store');
        Route::post('/packageStore', 'Us\DepoPackagesController@store');

        Route::resource('/sack', 'Us\SackController');
        Route::get('/sack/get/test', 'Us\SackController@test');
        Route::post('/sack/addInvoice', 'Us\SackController@addInvoice');
        Route::get('/sack/invoices/{id}', 'Us\SackController@invoices');

        Route::resource('/dispatch', 'Us\DispatchController');
        Route::post('/dispatch/addSack', 'Us\DispatchController@addSack');

        Route::get('/packages', 'Us\PackagesController@packages');
        Route::post('/packagePostParcelContents', 'Us\PackagesController@packagePostParcelContents');
        Route::get('/packagePostParcelConsignee', 'Us\PackagesController@packagePostParcelConsignee');
        Route::get('/packages/parcel/{id}', 'Us\PackagesController@getPackage');

        Route::get('/depo_packages', 'Us\DepoPackagesController@packages');
        Route::get('/depo_packages_confirmed', 'Us\DepoPackagesController@packagesConfirmed');
        Route::get('/packages/depo_package/{id}', 'Us\DepoPackagesController@getDepoPackage');
        Route::post('/confirmPackage', 'Us\DepoPackagesController@confirmPackage');
        Route::post('/removePackage', 'Us\DepoPackagesController@removePackage');

        Route::post('/addPackage', 'Us\DepoPackagesController@addPackage');


    });

});

    Route::get('/login', 'Us\AuthController@index')->name('usa_login_index');
    Route::post('/login', 'Us\AuthController@login')->name('login');
