<?php



Route::group(['middleware' => 'web'], function () {

    // These routes are require user to be logged in
    Route::group(['middleware' => 'auth:tr'], function () {

        // Constant subdomain
            Route::get('/', 'Tr\InvoiceController@test')->name('tr');
            Route::get('/logout', 'Tr\AuthController@logout');
            Route::get('/invoices/get/xml', 'Tr\InvoiceController@xml');
            Route::get('/invoices/mayeler', 'Tr\InvoiceController@mayeler');
            Route::get('/invoices/change-purchase/{purchase_no}', 'Tr\InvoiceController@changePurchase');
            Route::get('/invoices/get/manifest', 'Tr\InvoiceController@manifest');
            Route::get('/invoices/get/manifest2', 'Tr\InvoiceController@manifest2');
            Route::post('/invoices/check-storage', 'Tr\InvoiceController@addToOnTheWay');
            Route::post('/invoices/check-waiting', 'Tr\InvoiceController@addToForeignStorage');
            Route::post('/invoices/check-road', 'Tr\InvoiceController@addToStorage');
            Route::post('/invoices/check-native-storage', 'Tr\InvoiceController@addToCompleted');
            Route::post('/invoices/change-status', 'Tr\InvoiceController@changeStatus');
            Route::post('/invoices/addCustomStatus', 'Tr\InvoiceController@addCustomStatus');
            Route::post('/invoices/change-status-back', 'Tr\InvoiceController@changeStatusBack');
            Route::post('/invoices/change-all-stauses', 'Tr\InvoiceController@changeAllStatuses');
            Route::post('/invoices/send-fatura', 'Tr\InvoiceController@sendFatura');
            Route::get('/invoices/change-vehicle', 'Tr\InvoiceController@changeVehicle');
            Route::post('/invoices/send-invoiceless-message', 'Tr\InvoiceController@sendInvoicelessMessage');
            Route::get('/invoices/waybillOld/{id}', 'Tr\InvoiceController@waybill');
            Route::get('/invoices/waybill2/{id}', 'Tr\InvoiceController@waybill2');
            Route::get('/invoices/waybill/{id}', 'Tr\InvoiceController@waybillNew');
            Route::get('/invoices/waybillFatura/{id}', 'Tr\InvoiceController@waybillFatura');
            Route::get('/invoices/waybillFaturaOld/{id}', 'Tr\InvoiceController@waybillFaturaOld');
            Route::get('/invoices/user/{id}', 'Tr\InvoiceController@user');

//    Route::get('/invoices/get/test', 'Tr\InvoiceController@test');
            Route::get('/invoices/get/index', 'Tr\InvoiceController@test');
            Route::get('/invoices/get/stored', 'Tr\InvoiceController@stored');
            Route::get('/print-hawb/{productId}', 'Tr\ProductsController@printHawb')->name('tr_printHawb');
            Route::post('/api/invoice/set', 'Tr\APIController@setInvoiceDataById')->name('tr_setInvoiceDataById');
            Route::get('/api/invoice/{id}', 'Tr\APIController@getInvoiceDataById')->name('tr_getInvoiceDataById');
            Route::post('/invoices/change-status', 'Tr\InvoiceController@changeStatus');
            Route::post('/invoices/send-invoiceless-message', 'Tr\InvoiceController@sendInvoicelessMessage');
            Route::get('/invoices/change-purchase/{purchase_no}', 'Tr\InvoiceController@changePurchase');
            Route::post('/invoices', 'Tr\InvoiceController@store');
            Route::post('/invoices', 'Tr\InvoiceController@store');

            Route::resource('/sack', 'Tr\SackController');
            Route::get('/sack/get/test', 'Tr\SackController@test');
            Route::post('/sack/addInvoice', 'Tr\SackController@addInvoice');
            Route::get('/sack/invoices/{id}', 'Tr\SackController@invoices');
            Route::post('/sack/remove-invoices', 'Tr\SackController@removeInvoices');

            Route::resource('/dispatch', 'Tr\DispatchController');
            Route::post('/dispatch/addSack', 'Tr\DispatchController@addSack');
            Route::post('/dispatch/sendDispatch', 'Tr\DispatchController@sendDispatch');
            Route::post('/disptach/addFile', 'Tr\DispatchController@addFile');
            Route::get('/invoices/exportFile', 'Tr\DispatchController@exportFile');


            Route::get('/invoices/get/anbar', 'Tr\InvoiceController@anbar');
            Route::post('/invoices/addFatura', 'Tr\InvoiceController@addFatura');
            Route::post('/invoices/removeFatura', 'Tr\InvoiceController@removeFatura');
            Route::get('/invoices/get/faturalar', 'Tr\InvoiceController@faturalar');
            Route::get('/invoices/get/faturalarExcel', 'Tr\InvoiceController@faturalarExcel');

            Route::get('/daily', 'Tr\DepoPackagesController@daily');
            Route::post('/daily_data', 'Tr\DepoPackagesController@daily_data');
            Route::post('/removeDailyData', 'Tr\DepoPackagesController@removeDailyData');

            Route::get('/depo_packages', 'Tr\DepoPackagesController@packages');
            Route::get('/depo_packages_confirmed', 'Tr\DepoPackagesController@packagesConfirmed');
            Route::get('/packages/depo_package/{id}', 'Tr\DepoPackagesController@getDepoPackage');
            Route::post('/confirmPackage', 'Tr\DepoPackagesController@confirmPackage');
            Route::post('/addPackage', 'Tr\DepoPackagesController@addPackage');
            Route::post('/removePackage', 'Tr\DepoPackagesController@removePackage');
            Route::post('/removeReturn', 'Tr\ReturnsController@removeReturn');
            Route::post('/packageStore', 'Tr\DepoPackagesController@store');

            Route::get('/returns', 'Tr\ReturnsController@index');
            Route::post('/returns/changeStatus', 'Tr\ReturnsController@changeStatus');
            Route::post('/returns/updateTrackingNumber', 'Tr\ReturnsController@updateTrackingNumber');
            Route::post('/returns/sendReturn', 'Tr\ReturnsController@sendReturn');
            Route::get('/returns/get_return/{id}', 'Tr\ReturnsController@getReturn');


    });
});

    Route::get('/login', 'Tr\AuthController@index')->name('login');
    Route::post('/login', 'Tr\AuthController@login')->name('tr_login');
