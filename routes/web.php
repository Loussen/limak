<?php

Auth::routes();
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/password/reset2', '\App\Http\Controllers\Site\FrontController@passwordResetWithPhone');

// CP Panel
Route::group(['middleware' => 'auth:admin', 'namespace' => 'Admin'], function (){
    Route::get('/cp', function () {
        return view('admin/welcomeadmin');
    });
});
// SIte papkasinin icindedirpayment-balance-success
Route::group(['namespace' => 'Site','as' => 'front.', 'middleware' => 'web'], function() {
    Route::get('/', 'FrontController@index')->name('main'); //OK
    Route::get('/countries', 'CountryController@index')->name('country'); //OK
    Route::get('/calculator', 'CalculatorController@index')->name('calculator'); //OK
    //Route::get('/premium', 'CalculatorController@premium')->name('premium'); //OK
    Route::post('/calculate', 'CalculatorController@calculate'); //OK
    Route::get('/calculate-currency', 'CalculatorController@calculateCurrency')->name('currency'); //Ok
    Route::get('/shops/country/{country}', 'ShopsController@index')->name('shop'); //OK
    Route::resource('/contact', 'ContactController'); //OK
    Route::get('/istifadeci-qaydalari', 'FrontController@userRules')->name('static.pages.userRules'); //OK
    Route::get('/suallar', 'FrontController@userQuestions')->name('static.pages.userQuestions'); //OK
    Route::get('/blog/{id}/{slug}', 'FrontController@newsIn'); //OK
    Route::get('/blog/{id}', 'FrontController@newsIn'); //OK

    Route::get('/news-and-announcements/{id}', 'FrontController@newsIn'); //lazimsiz
    Route::get('/news-and-announcements/{id}/{slug}', 'FrontController@newsIn'); //lazimsiz
    Route::get('/payment-balance-success', 'Panel\PaymentForBalanceController@getResponseOfPayment'); // Balansdan odenis
    Route::get('/success', 'FrontController@success')->name('success');
    Route::get('/password-sent', 'FrontController@passwordSent')->name('passwordSent2');
    Route::get('/anbar-tr', 'HomeController@anbar')->name('anbar');

    Route::post('/payment-callback', 'PaymentForProductController@getResponseOfPayment');// paytr    callbak post url
    Route::post('/payment-order-notify', 'OrderController@paymentNotify'); // paytr 2ci addim
    Route::get('/get-currency/{type}', 'FrontController@getCurrency');

    // payment callback
    Route::get('/payment-order-success', 'FrontController@paymentSuccess'); // Kart odenisi
    Route::get('/payment-success', 'FrontController@paymentSuccessBalance'); // Balans odenisi
    Route::get('/balancePayment', 'PaymentController@millionApi');
    Route::get('/{slug}', 'FrontController@staticPages')->name('static.pages.show');
});


Route::group(['namespace' => 'Front', 'middleware' => 'web'], function() {

});


//  Site papkasinin icindedir   /site/..
Route::group([ 'middleware' => ['web', 'auth'], 'namespace' => 'Site','prefix' => 'site','as' => 'front.'], function() { // authenticated routes
    Route::get('/invoice', 'InvoiceController@index')->name('invoice.index');
    Route::get('/productTypes', 'InvoiceController@productTypes')->name('invoice.product.types');
    Route::post('/invoice', 'InvoiceController@store')->name('invoice.store');
    Route::post('/invoice/edit', 'InvoiceController@edit')->name('invoice.edit');
/*
    Route::get('/track', 'Panel\TrackController@index');
    Route::get('/track/{type}', 'Panel\TrackController@index_new');
    Route::get('/track2/{type}', 'Panel\TrackController@index2');*/

});


// site papkasinin icinde ama site olmadan bir basa linkler
Route::group([ 'middleware' => ['web', 'auth'], 'namespace' => 'Site'], function() { // authenticated routes
    Route::post('/order/parse', 'OrderController@parse');
    Route::post('/order/link/insert', 'OrderController@store');
    Route::post('/order/link/insertBalance', 'OrderController@storeBalance');
});

// New User Panel Vue
Route::group([ 'middleware' => ['auth', 'isblocked'], 'namespace' => 'Site\Panel','prefix' => 'site'], function() { // authenticated routes
    Route::get('/user-panel', 'PanelController@index')->name('new-user-panel');
    Route::post('/user-panel', 'PanelController@index')->name('new-user-panel2');

    Route::get('/premium', 'PanelController@premium')->name('new-user-panel-premium');
    Route::post('/premium', 'PanelController@premium')->name('new-user-panel-premium');

    Route::get('/order/link/insert', 'OrderController@storeLink');


});

// New
/*Route::group([ 'middleware' => ['auth', 'isblocked'], 'namespace' => 'Site\Panel'], function() { // authenticated routes
    Route::get('/user-panel/get-user-data', 'PanelController@userData');
    Route::get('/user-panel/get-invoices/{type}', 'InvoicesController@index');
    Route::get('/user-panel/get-allInvoices-count', 'InvoicesController@allInvoicesCount');
    Route::get('/user-panel/get-invoices-count', 'InvoicesController@invoiceCount');
});*/

Route::group([ 'middleware' => ['auth', 'isblocked'], 'namespace' => 'Site\Panel'], function() { // authenticated routes
    Route::get('/panel/get-user-data', 'PanelController@userData');
    Route::get('/panel/clients', 'PanelController@getCorporateClients');
    Route::post('/panel/add-client', 'PanelController@addClient');
    Route::post('/panel/edit-client', 'PanelController@editClient');
    Route::get('/panel/get-invoices/{type}', 'InvoicesController@index');
    Route::get('/panel/get-invoice/{id}', 'InvoicesController@view');
    Route::get('/panel/get-invoices-count', 'InvoicesController@invoiceCount');
    Route::post('/panel/delete-invoice', 'InvoicesController@deleteInvoice');
    Route::get('/panel/order-status', 'InvoicesController@orderStatus');

});

//user-panel
Route::group([ 'middleware' => ['auth', 'isblocked'], 'namespace' => 'Front\Panel'], function() { // authenticated routes
    //Route::get('/user-panel', 'PanelController@index')->name('user-panel');
    Route::get('/user-panel/get-user-data', 'PanelController@userData');
    Route::get('/user-panel/get-user-data1', 'PanelController@userData1');
    Route::get('/user-panel/get-orders/{type}', 'OrderController@index');
    Route::get('/user-panel/get-orders2/{type}', 'OrderController@index2');
    Route::post('/user-panel/delete-basket/', 'OrderController@deleteBasket');
    Route::post('/user-panel/update-basket/', 'OrderController@updateBasket');
    Route::post('/user-panel/update-product/', 'OrderController@updateProduct');
    //Route::get('/user-panel/track', 'TrackController@index');
    Route::get('/user-panel/get-countries', 'InvoicesController@countries');
    Route::get('/user-panel/get-invoices/{type}', 'InvoicesController@index');
    Route::get('/user-panel/home_courier_invoices', 'InvoicesController@homeForCourier');
    Route::get('/user-panel/get-invoices2/{type}', 'InvoicesController@index2');
    Route::get('/user-panel/get-invoices-count', 'InvoicesController@invoiceCount');
    Route::get('/user-panel/get-allInvoices-count', 'InvoicesController@allInvoicesCount');
    Route::get('/user-panel/get-settings-data', 'SettingsController@index');
    Route::get('/user-panel/get-balance', 'BalanceController@index');
    Route::get('/user-panel/get-try-balance', 'BalanceController@indexTry');
    Route::post('/user-panel/post-settings-data', 'SettingsController@store');
    Route::post('/user-panel/post-settings-data-profile', 'SettingsController@storeProfile');
    Route::post('/user-panel/post-settings-data-passport', 'SettingsController@storePassport');
    Route::post('/user-panel/post-settings-data-password', 'SettingsController@storePassword');
    Route::post('/user-panel/post-courier-data', 'CourierController@store');
    Route::post('/user-panel/post-courier-data1', 'CourierController@store1');
    Route::post('/user-panel/post-transfer-data', 'CourierController@transferStore');
    Route::get('/user-panel/get-courier-data', 'CourierController@index');
    Route::get('/user-panel/get-courier-data2', 'CourierController@index2');
    Route::get('/user-panel/get-transfer-data', 'CourierController@indexTransfer');
    Route::get('/user-panel/get-complaints', 'QuestionsController@getComplaints');
    Route::get('/user-panel/get-complaints', 'QuestionsController@getComplaints');

    // Asistant
//    Route::post('/user-panel/get-questions', 'QuestionsdController@getDefaultQuestions');
//    Route::post('/user-panel/get-question', 'QuestionsdController@getDefaultQuestion');
//    Route::get('/user-panel/get-max-step', 'QuestionsdController@getMaxStepQuestions');

    Route::post('/user-panel/get-questions', 'QuestionsddController@getDefaultQuestions');
    Route::post('/user-panel/get-question', 'QuestionsddController@getDefaultQuestion');
    Route::get('/user-panel/get-max-step', 'QuestionsddController@getMaxStepQuestions');


//    Route::get('user-panel/get-complaints1', 'QuestionsController@getComplaints1');
    Route::get('/user-panel/get-complaint-messages/{id}', 'QuestionsController@getComplaintMessages');
    Route::post('/user-panel/add-complaint', 'QuestionsController@addComplaint');
    Route::get('/user-panel/complaint-types', 'QuestionsController@getComplaintTypes');
    Route::get('/user-panel/change-seen-status/{id}', 'QuestionsController@changeSeen');
    Route::post('/user-panel/send-message', 'QuestionsController@sendMessagetoAdmin');
    // for payment
    Route::post('/user-panel/pay', 'PanelController@pay');
    Route::post('/user-panel/pay-courier', 'PanelController@payOnlyCourier');
    Route::post('/user-panel/pay-courier1', 'PanelController@payOnlyCourier1');
    Route::post('/user-panel/cancel-courier', 'PanelController@cancelOnlyCourier');
    Route::get('/user-panel/get-profit', 'PanelController@getProfit');
    Route::get('/user-panel/get-expense', 'PanelController@getUserExpense');
    Route::get('/user-panel/get-log-balance', 'PanelController@getLogBalance');
    Route::get('/user-panel/get-log-premium', 'PanelController@getLogPremium');
    Route::get('/user-panel/get-user-code', 'PanelController@getUserCode');
    Route::post('/user-panel/increase-balance', 'PaymentForBalanceController@requestForPayment');
    Route::post('/user-panel/pay-basket-price', 'InvoicesController@payBasket');
    Route::post('/user-panel/pay-invoice-price', 'InvoicesController@payInvoice');

    Route::get('/user-panel/getPaymentData/{id}', 'PanelController@getPaymentData');
});

Route::post('/api/login', '\App\Http\Controllers\Api\AuthController@login');
Route::post('/api/register', '\App\Http\Controllers\Api\AuthController@register');
Route::post('/api/reset-password', '\App\Http\Controllers\Api\AuthController@resetPassword');
Route::get('/info/user-rules', 'Api\InfoController@getUserRulesData');
Route::get('/info/order-status', 'Api\InfoController@orderStatus');
Route::get('/info/invoice-status', 'Api\InfoController@invoiceStatus');
Route::get('/info/courier-status', 'Api\InfoController@courierStatus');


Route::post('/api/company/invoice', '\App\Http\Controllers\Company\ApiController@invoice');
Route::post('/api/company/get-invoice', '\App\Http\Controllers\Company\ApiController@getInvoice');

Route::post('/apiBon/get-product', '\App\Http\Controllers\Company\BonController@index');
