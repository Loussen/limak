<?php
use App\Message;
Route::group(['middleware' => 'auth:admin', 'namespace' => 'Cp'], function () {
    Route::get('/welcome', 'WelcomeController@index');
    Route::get('/orders', 'OrdersController@orders');
    Route::get('/orders/statuses', 'OrdersController@statuses');
    Route::get('/orders/regions', 'OrdersController@regions');
    Route::get('/orders/countries', 'OrdersController@countries');
    Route::post('/orders/changeStatus', 'OrdersController@changeStatus');
    Route::post('/orders/changeRegion', 'OrdersController@changeRegion');
    Route::post('/orders/changeCountry', 'OrdersController@changeCountry');
    Route::post('/orders/changeRegionCountry', 'OrdersController@changeRegionCountry');
    Route::get('/orders/getProductsByPackage/{id}', 'OrdersController@getProductsByPackage');
    Route::get('/orders/delete-invoice/{id}', 'OrdersController@deleteInvoice');


    Route::delete('/orders/delete/{id}', 'OrdersController@delete');
    Route::post('/orders/update/{product}','OrdersController@update');
    Route::post('/orders/problem/{product}','OrdersController@problem');
    Route::post('/orders/backInvoice','OrdersController@backInvoice');
    Route::get('/incomingOrders', 'OrdersController@incoming');
    Route::get('/executingOrders', 'OrdersController@executing');
    Route::post('/acceptOrder/{id}', 'OrdersController@acceptOrder');
    Route::get('/getOrderDetailByUser/{id}', 'OrdersController@getOrderDetailByUser');
    Route::get('/getOrderDetailByUser5/{id}', 'OrdersController@getOrderDetailByUser5');
    Route::get('/invoicesByUser/', 'OrdersController@invoicesByUser');
    Route::get('/invoicesByUserWithId/{id}', 'OrdersController@invoicesByUserWithId');
    Route::get('/invoicesByUser2/', 'OrdersController@invoicesByUser2');
    Route::get('/invoicesByUser3/', 'OrdersController@invoicesByUser3');
    Route::get('/allInvoices', 'OrdersController@allInvoices');
    Route::get('/noInvoices', 'OrdersController@noInvoices'); // elave olunmus inyoys olmayanlar
    Route::get('/noInvoicesForeign', 'OrdersController@noInvoicesForeign'); // xaricden elave olunmus inyoys olmayanlar
    Route::get('/getInvoiceProducts/{id}', 'OrdersController@getInvoiceProducts'); // invoys id-ye gore melumatlari getirmek
    Route::get('/hasInvoices', 'OrdersController@hasInvoices'); // elave olunmus inyoys olmayanlar

    Route::get('/cash/accounts', 'CashController@getRegionAccounts');
    Route::get('/cash/account/{id}', 'CashController@getAccount');
    Route::post('/cash', 'CashController@store');
    Route::get('/cash/get-log-balance-by-user', 'CashController@getLogBalanceByUser');
    Route::post('/cashBack', 'CashController@cashBack');
    Route::post('/cash/pay', 'CashController@add');
    Route::get('/accounts', 'AccountController@index');
    Route::post('/accounts/add', 'AccountController@add');
    Route::get('/accounts/forOrderPayments', 'AccountController@forOrderPayments');
    Route::post('/accounts/forOrderPayments', 'AccountController@forOrderPayments2');
    Route::get('/accounts/cash', 'CashController@show');
    Route::get('/accounts/logs/{log}', 'AccountController@list');
    Route::get('/accounts/logsAccount/{log}', 'AccountController@listAccount');
    Route::get('/accounts/logsAccountPrint/{log}', 'AccountController@listAccountPrint');
    Route::get('/accounts/repayments/{type}', 'AccountController@getRepayments');
    Route::get('/accounts/repaymentOrders/{repayment}', 'AccountController@getRepaymentDetails');
    Route::post('/accounts/repaymentExecute/{repayment}', 'AccountController@RepaymentExecute');
    Route::delete('/accounts/repayment/{repayment}', 'AccountController@RepaymentDelete');
    Route::get('/accounts/eft', 'AccountController@getEFTpayments');
    Route::post('/accounts/eft', 'AccountController@storeEFT');
    Route::post('/accounts/backToCard', 'AccountController@storeBackToCard');
    Route::post('/accounts/backToCard2', 'AccountController@storeBackToCard2');
    Route::post('/accounts/changeAccountId', 'AccountController@changeAccountId');

    Route::get('/cash/show', 'CashController@show');
    Route::post('/cash/store', 'CashController@store');
    Route::post('/cash/storeTry', 'CashController@storeTry');
    Route::post('/cash/handleUserBalance', 'CashController@updateUserBalance');
    Route::post('/cash/handleUserBalanceTry', 'CashController@updateUserBalanceTry');


    //Kassa
    Route::get('/kassa/accounts', 'KassaController@getRegionAccounts');
    Route::get('/kassa/account/{id}', 'KassaController@getAccount');
    Route::post('/kassa', 'KassaController@store');
    Route::get('/kassa/get-log-balance-by-user', 'KassaController@getLogBalanceByUser');
    Route::post('/kassaBack', 'KassaController@kassaBack');
    Route::post('/kassa/pay', 'KassaController@add');
    Route::get('/accounts/kassa', 'KassaController@show');
    Route::get('/kassa/show', 'KassaController@show');
    Route::post('/kassa/store', 'KassaController@store');
    Route::post('/kassa/storeTry', 'KassaController@storeTry');
    Route::post('/kassa/handleUserBalance', 'KassaController@updateUserBalance');
    Route::post('/kassa/handleUserBalanceTry', 'KassaController@updateUserBalanceTry');




    Route::post('/search-invoice', 'SearchController@invoices');
    Route::get('/executingOrdersNew', 'OrdersController@executingNew');
    Route::get('/hasInvoicesNew', 'OrdersController@hasInvoicesNew'); // elave olunmus inyoys olmayanlar
    Route::get('/hasInvoicesLate', 'OrdersController@hasInvoicesLate'); // elave olunmus inyoys olmayanlar

    Route::get('/depotDetails','DepotController@getDetails');
    Route::get('/depot/delivered','DepotController@getDeliveredProducts');
    Route::get('/depot/deliveredAll','DepotController@getDeliveredProductsAll');
    Route::get('/depot/custom','DepotController@getCustomProducts');
    Route::get('/depot/regionInvoices','DepotController@getRegionInvoices');
    Route::post('/depot/addCustomProduct','DepotController@addCustomProduct');
    Route::post('/depot/deleteCustomProduct','DepotController@deleteCustomProduct');
    Route::get('/depot/15days','DepotController@get15DaysInDepot');
    Route::get('/depot/addDepotStatusForAll','DepotController@addDepotStatusForAll');
    Route::get('/depot/45days','DepotController@get45DaysInDepot');
    Route::get('/depot/payed','DepotController@getPayedProducts');
    Route::get('/depot/packages','DepotController@packages');
    Route::get('/depot/byDepotId/{depot}','DepotController@show');
    Route::get('/depot/byUserId/{uniqid}','DepotController@byUser');
    Route::get('/depot/changeInvoicePlace/{invoice_id}/{barcode}','DepotController@changeInvoicePlace');
    Route::post('/depot/changeInvoicePlaceAll','DepotController@changeInvoicePlaceAll');

    Route::get('/transfer/orders-waiting', 'TransferController@getTransferOrders');
    Route::post('/transfer/addTransfer', 'TransferController@addTransfer');

    Route::get('/users/index','UsersController@index');
    Route::get('/users/get','UsersController@getUsers');
    Route::get('/users/getUser','UsersController@getUser');
    Route::get('/users/clients','UsersController@getClients');
    Route::get('/users/repayments','UsersController@getRePayments');
    Route::get('/users/basket','UsersController@basket');
    Route::get('/users/reject','UsersController@reject');
    Route::get('/users/executing','UsersController@executing');
    Route::get('/users/incoming','UsersController@incoming');
    Route::get('/users/waiting','UsersController@waiting');
    Route::get('/users/foreign_stock','UsersController@foreign_stock');
    Route::get('/users/on_the_way','UsersController@on_the_way');
    Route::get('/users/home_stock','UsersController@home_stock');
    Route::get('/users/completed','UsersController@completed');
    Route::get('/users/has_courier','UsersController@has_courier');
    Route::get('/users/all_invoices','UsersController@all_invoices');
    Route::get('/users/balanceInfo/{id}','UsersController@balanceInfo');
    Route::post('/users/save_comment','UsersController@saveComment');
    Route::get('/users/testYusif','UsersController@testYusif');
    Route::get('/users/testYusif2','UsersController@testYusif2');
    Route::get('/users/sendEmail','UsersController@sendEmail');
    Route::get('/users/getSmsTemplates','UsersController@getSmsTemplates');
    Route::post('/users/addBalance','UsersController@addBalance');
    Route::post('/users/addBalanceTry','UsersController@addBalanceTry');
    Route::get('/users/sendSmsForAll','UsersController@sendSmsForAll');
    Route::post('/users/blockUser','UsersController@blockUser');
    Route::post('/users/blackListUser','UsersController@blackListUser');
    Route::post('/users/changePin','UsersController@changePin');
    Route::post('/users/changeEmail','UsersController@changeEmail');
    Route::get('/users/hesabat','UsersController@hesabat');


    Route::get('/courier/act/{id}','HomeController@act');
    Route::get('/courier/orders-waiting','CourierController@getCourierOrders');
    Route::get('/courier/orders-print','CourierController@getCourierOrdersPrint');
    Route::get('/courier/getCourierData','CourierController@getCourierInvoicesData');
    Route::get('/courier/reject-order', 'CourierController@rejectOrder');
    Route::post('/courier/addCourier', 'CourierController@addCourier');
    Route::post('/courier/complete-order', 'CourierController@completeOrder');
    Route::get('/courier/check-depot', 'CourierController@checkDepot');
    Route::get('/courier/print-data', 'CourierController@printData');


    Route::get('accountant/userBalance','AccountController@customerBalances');
    Route::get('accountant/getLogBalances','AccountController@getLogBalances');
    Route::get('accountant/getDispatch','AccountController@getDispatch');
    Route::get('accountant/dispatchs','AccountController@dispatchs');

    Route::get('expenses', "ExpensesController@expenses");
    Route::get('expenses2', "ExpensesController@expenses2");
    Route::get('expenseDetails', "ExpensesController@expenseDetails");
    Route::get('expense/types', "ExpensesController@expenseTypes");
    Route::get('expense/accounts', "ExpensesController@accounts");
    Route::get('accountInfo', "ExpensesController@accountInfo");
    Route::post('/expense/type', "ExpensesController@storeExpenseType");
    Route::post('/expense', "ExpensesController@storeExpense");
    Route::post('/expense', "ExpensesController@storeExpense");
    Route::get('admin/getRole','AdminsController@getAdminRole');
    Route::get('admin/getRegion','AdminsController@getAdminRegion');
    Route::get('admin/getRegions','AdminsController@getAdminRegions');
    Route::get('admin/getAdmins','AdminsController@getAdmins');
    Route::resource('/news', 'NewsController');
    Route::resource('/questions', 'QuestionsController');
    Route::resource('/pages', 'PagesController');

    Route::get('/customs/manifest','CustomController@manifest');
    Route::get('/customs/mayeler','CustomController@mayeler');
    Route::get('/customs/manifestLiquid','CustomController@manifestLiquid');
    Route::get('/customs/manifestUsa','CustomController@manifestUsa');
    Route::get('/customs/manifestUsaBaku','CustomController@manifestUsaBaku');
    Route::get('/customs/manifest2','CustomController@manifest2');
    Route::get('/customs/xml','CustomController@xml');
    Route::get('/customs/xmlLiquid','CustomController@xmlLiquid');
    Route::get('/customs/xmlUsa','CustomController@xmlUsa');
    Route::get('/customs/xml2','CustomController@xml2');
    Route::get('/customs/xmlPage/{page}','CustomController@xmlPage');
    Route::get('/customs/xmlParts','CustomController@xmlParts');
    Route::get('/customs/regions','CustomController@regions');
    Route::get('/customs/liquid','CustomController@liquid');

    Route::get('/allInvoicesStatistic', 'StatisticController@allInvoices');


    Route::resource('/shops', 'ShopsController');
    Route::resource('/shop-types', 'ShopTypesController');

    Route::resource('/apis', 'ApisController');
    Route::resource('/roles', 'RolesController');
    Route::resource('/admins', 'AdminsController');

    Route::post('orders/sendSMS','OrdersController@sendSMS');
    Route::post('orders/sendEmail','OrdersController@sendEmail');

    Route::get('/statistic/getShippingPrices','StatisticController@getShippingPrices');
    Route::get('/statistic/getShippingPricesUsa','StatisticController@getShippingPricesUsa');



});
