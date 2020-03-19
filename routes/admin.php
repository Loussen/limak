<?php
use App\Message;
// delete news file


Route::get('/login', 'Auth\LoginController@showAdminLoginForm')->name('admin.login.form');
Route::get('/admin-user', function() {
    return response()->json(Auth::guard('admin')->user(), 200);
});
Route::get('/register', 'Auth\RegisterController@showAdminRegisterForm')->name('admin.register');
Route::get('/logout', 'Auth\LoginController@adminLogout')->name('admin.logout');

Route::post('/login', 'Auth\LoginController@adminLogin')->name('admin.login');
Route::post('/register', 'Auth\RegisterController@createAdmin');

Route::group(['middleware' => 'auth:admin', 'namespace' => 'Admin'], function () {
    Route::post('/delete-news-file', 'NewsController@deleteFile');

    Route::get('/welcome-admin', 'WelcomeController@index');
    Route::resource('/news', 'NewsController');
    Route::get('/order/list', 'OrderController@index');
    Route::resource('/shops', 'ShopsController');
    Route::resource('/partners', 'PartnersController');
    Route::resource('/faq', 'FaqController');
    Route::resource('/countries', 'CountriesController');
    Route::resource('/roles', 'RolesController');
    Route::resource('/apis', 'ApisController');
    Route::resource('/admins', 'AdminsController');
    Route::resource('/slider', 'SliderController');
    Route::resource('/all-invoices', 'AllInvoicesController');
    Route::get('/contact', 'ContactController@index')->name('admin.contact.index');

    Route::resource('/invoices', 'InvoiceController');
    Route::get('/invoices/get/xml', 'InvoiceController@xml');
//    Route::get('/invoices/status', 'InvoiceController@getDataStatus');
    Route::get('/invoices/get/test', 'InvoiceController@test');
    Route::get('/invoices/get/test111', 'InvoiceController@test111');
    Route::get('/invoices/change-purchase/{purchase_no}', 'InvoiceController@changePurchase');
    Route::get('/invoices/get/manifest', 'InvoiceController@manifest');
    Route::get('/invoices/get/manifest2', 'InvoiceController@manifest2');
    Route::post('/invoices/check-storage', 'InvoiceController@addToOnTheWay');
    Route::post('/invoices/check-waiting', 'InvoiceController@addToForeignStorage');
    Route::post('/invoices/check-road', 'InvoiceController@addToStorage');
    Route::post('/invoices/check-native-storage', 'InvoiceController@addToCompleted');
    Route::post('/invoices/change-status', 'InvoiceController@changeStatus');
    Route::post('/invoices/change-all-stauses', 'InvoiceController@changeAllStatuses');
    Route::post('/invoices/send-invoiceless-message', 'InvoiceController@sendInvoicelessMessage');
    Route::get('/invoices/waybill/{id}', 'InvoiceController@waybill');
    Route::resource('/sack', 'SackController');
    Route::get('/sack/get/test', 'SackController@test');
    Route::post('/sack/addInvoice', 'SackController@addInvoice');
    Route::get('/sack/invoices/{id}', 'SackController@invoices');
    Route::resource('/dispatch', 'DispatchController');
    Route::post('/dispatch/addSack', 'DispatchController@addSack');

    Route::get('/invoiceless', 'InvoicelessController@index')->name('invoiceless.index');
    Route::post('/invoiceless', 'InvoicelessController@store')->name('invoiceless.store');
    Route::post('/invoiceless/send/{id}', 'InvoicelessController@send')->name('invoiceless.send');
    Route::get('/invoiceless/done/{id}', 'InvoicelessController@done')->name('invoiceless.done');

    Route::get('/get-admin-data', 'AdminsController@getAdminId')->name('getAdminData');
    Route::get('/accountants', 'AdminsController@getAccountants')->name('getAccountants');
    Route::post('/rejection', 'ProductsController@rejection')->name('rejection');
    Route::post('/refusal', 'RelUserProductsController@refusal')->name('refusal');
    Route::post('/changeExtrasLink', 'ProductsController@changeExtrasLink')->name('changeExtrasLink');  // new

    Route::post('/order/invoice-upload', 'ProductsController@invoiceUpload')->name('invoiceUpload');
    Route::post('/order/invoice-upload_new', 'ProductsController@invoiceUpload_new')->name('invoiceUpload_new');
    Route::post('/order/invoice-upload/change', 'ProductsController@updateInvoice')->name('invoiceChangeFile');
    Route::post('/order/finish', 'AdminsAcceptedProductsController@finishOrder')->name('finishOrder');
    Route::post('/order/ordered', 'RelUserProductsController@ordered')->name('ordered');
    Route::post('/order/send/invoiceData', 'InvoiceController@setInvoiceDataByBuyer')->name('sendInvoiceData');
    Route::post('/order/update/invoiceData', 'InvoiceController@updateInvoiceDataByBuyer')->name('updateInvoiceData');

    Route::get('/my-orders/list', 'RelUserProductsController@myOrders')->name('myOrders');
    Route::get('/orders/list', 'RelUserProductsController@orders')->name('orders');
    Route::get('/will-upload-invoices/list', 'RelUserProductsController@willUploadInvoices')->name('willUploadInvoices');
    Route::post('/calc-additional-price', 'RelUserProductsController@calcAddPrice')->name('calcAddPrice');


    Route::get('/orders/log', 'AdminsAcceptedProductsController@getFinishedOrdersLog')->name('ordersLog');
    Route::get('/orders', 'AdminsAcceptedProductsController@getOrders')->name('orders');
    Route::get('/orders/detail/{id}', 'AdminsAcceptedProductsController@getOrderDetail')->name('orderDetail');
    Route::get('/orders/detail/by-admin/{id}', 'AdminsAcceptedProductsController@getOrderDetailByAdmin')->name('orderDetail');
    Route::get('/orders/detail/by-user/{id}', 'AdminsAcceptedProductsController@getOrderDetailByUser')->name('orderDetailByUser'); // new
    Route::get('/will-upload-invoice/detail/by-admin/{id}', 'AdminsAcceptedProductsController@getWillUploadInvoiceDetailByAdmin')->name('willUploadInvoiceDetail');
    Route::get('/orders/detail/log/by-admin/{id}', 'AdminsAcceptedProductsController@getOrderLogDetailByAdmin')->name('orderLogDetail');
    Route::get('/accept', 'AdminsAcceptedProductsController@index')->name('accept');
    Route::post('/accept-order', 'AdminsAcceptedProductsController@acceptOrder')->name('acceptOrder');
    Route::post('/addBalance', 'UsersController@addBalance')->name('addBalance');
    Route::post('/accept/order/{id}', 'AdminsAcceptedProductsController@order')->name('order');

    Route::get('/courier', 'CourierController@index')->name('courier');

    Route::get('/courier/orders-answer-waiting', 'CourierController@getCourierAnswerOrders')->name('getCourierAnswerOrders');
    Route::get('/courier/orders-waiting', 'CourierController@getCourierOrders')->name('getCourierOrders');
    Route::get('/courier/reject-order', 'CourierController@rejectOrder');
    Route::get('/courier/check-depot', 'CourierController@checkDepot');
    Route::get('/courier/orders-delivered', 'CourierController@getDeliveredOrders')->name('getDeliveredOrders');
    Route::get('/courier/courier-users', 'CourierController@getCourierUsers')->name('getCourierUsers');
    Route::get('/courier/orders-delivered2', 'CourierController@courierDeliveryLogs')->name('courierDeliveryLogs');
    Route::post('/courier/selected', 'CourierController@courierSelected')->name('courierSelected');
    Route::post('/courier/complete-order', 'CourierController@completeOrder')->name('completeOrder');
    Route::post('/courier/give-to-depot', 'CourierController@giveToDepot')->name('giveToDepot');
    Route::get('/courier/orders-count', 'CourierController@ordersCount');
    Route::get('/courier/orders-complete-delivered/{id}', 'CourierController@completeDelivereOrder')->name('completeDeliveredOrder');
    Route::post('/courier/finish', 'CourierController@productDelivered')->name('productDelivered');
    Route::get('/courier/logs', 'CourierController@courierDeliveryLogs')->name('courierDeliveryLogs');
    Route::post('/courier/addCourier', 'CourierController@addCourier');
    Route::get('/courier/getCourierInvoices', 'CourierController@getCourierInvoices');

    Route::post('/problems/lost-packages', 'ProblemsController@searchLostPackages');
    Route::post('/problems/user-complains', 'ProblemsController@searchUserComplains');
    Route::get('/problems/complaint-types', 'ProblemsController@getComplaintTypes');
    Route::get('/problems/complaints', 'ProblemsController@getComplaints');
    Route::get('/problems/user-complaints', 'ProblemsController@getUserComplaints');
//    Route::post('/problems/add-complaint', 'ProblemsController@addComplaint');
    Route::get('/problems/get-complaint/{id}', 'ProblemsController@getComplaint');
    Route::post('/problems/send-message', 'ProblemsController@sendMessagetoUser');
//    Route::get('/problems/solve-complaint/{id}', 'ProblemsController@solveComplaint');
    Route::get('/problems/formal-complaints/{type}', 'ProblemsController@formalComplaints')->name('formalComplaints');

    Route::get('/user/list', 'UsersController@index')->name('users.list');
    Route::get('/user/allData/{id}', 'UsersController@allData')->name('users.allData');
    Route::get('/user/allData', 'UsersController@allData')->name('users.allData');
    Route::post('/user/sendEmail', 'UsersController@sendEmail')->name('users.sendEmail');
    Route::get('/user/sendEmailForAll', 'UsersController@sendEmailForAll')->name('users.sendEmail');
    Route::post('/user/sendSms', 'UsersController@sendSms')->name('users.sendSms');
    Route::get('/user/sendSmsForAll', 'UsersController@sendSmsForAll')->name('users.sendSms');

    Route::post('/product/comment', 'ProductsController@comment')->name('product.saveComment');
    Route::post('/product/saveRealPrice', 'ProductsController@saveRealPrice')->name('product.saveRealPrice');


    Route::get('/get-product-data-by-id/{id}', 'ProductsController@getProductDataById')->name('getProductDataById');
    Route::get('/print-hawb/{productId}', 'ProductsController@printHawb')->name('printHawb');
    Route::get('/user/list/block/{id}', 'UsersController@block')->name('users.block');

    Route::post('/api/invoice/set', 'APIController@setInvoiceDataById')->name('setInvoiceDataById');
    Route::get('/api/invoice/{id}', 'APIController@getInvoiceDataById')->name('getInvoiceDataById');
});

/*
 * Static Page Generator
 */
Route::group(['middleware' => 'auth:admin', 'namespace' => 'Admin\StaticPages'],function () {
    Route::get('/static-pages', 'PagesController@index')->name('sp.index');
    Route::get('/static-pages/list', 'PagesController@list')->name('sp.list');
    Route::post('/static-pages/insert', 'PagesController@insert')->name('sp.insert');
    Route::post('/static-pages/update', 'PagesController@update')->name('sp.update');
    Route::get('/static-pages/show/{id}', 'PagesController@show')->name('sp.show');

    // Generator
    Route::post('/generator/insert' , 'ContentStaticPagesController@insert');
    Route::patch('/generator/update-text' , 'TextsController@update');
    Route::patch('/generator/update-video' , 'MediasController@update');
    Route::post('/generator/update-image' , 'MediasController@update');
    Route::get('/generator/list/{id}' , 'ContentStaticPagesController@list');

    // Content Types
    Route::get('/content-types/list', 'ContentTypesController@list')->name('ct.list');
});
/*
 * Messenger
 */
Route::group(['middleware' => 'auth:admin', 'namespace' => 'Admin\Messenger'], function () {
    Route::get('/messages', 'MessageController@index')->name('msg.index');
    Route::get('/messages/list', 'MessageController@list')->name('msg.list');
    Route::get('/messages/show/{id}', 'MessageController@show')->name('msg.show');
    Route::post('/messages/post', 'MessageController@post')->name('msg.post');
});
/*
 * Messenger
 */
Route::group(['middleware' => 'auth:admin', 'namespace' => 'Admin\Depot'], function () {
    Route::get('/depot', 'DepotController@index')->name('depot.index');
    Route::get('/depot/list', 'DepotController@list')->name('depot.list');
    Route::get('/depot/cashier/list', 'DepotController@cashier')->name('depot.cashier.list');
    Route::get('/depot/payed/list', 'DepotController@payed')->name('depot.payed.list');
    Route::post('/depot/payed/finish', 'DepotController@finish')->name('depot.payed.finish'); //new
    Route::post('/depot/cashier/pay', 'DepotController@pay')->name('depot.cashier.pay');
    Route::post('/depot/editor/insert', 'DepotController@editorInsert')->name('depot.editor.insert');
    Route::post('/depot/editor/update', 'DepotController@editorUpdate')->name('depot.editor.update');
    Route::post('/depot/editor/delete', 'DepotController@editorDelete')->name('depot.editor.delete');
    Route::get('/depot/editor/list', 'DepotController@editorList')->name('depot.editor.list');
    Route::post('/depot/store/insert', 'DepotController@storeInDepot')->name('depot.storeInDepot.insert');
    Route::get('/depot/check/role', 'DepotController@role')->name('depot.storeInDepot.role');
});
/*
 * Accountant
 */
Route::group(['middleware' => 'auth:admin', 'namespace' => 'Admin\Accountant'], function () {
    Route::resource('/accountant-way', 'OnTheWayController');
    Route::resource('/rejection-for-accountant', 'RejectionController');
    Route::get('/rejection-for-accountant/products/{id}', 'RejectionController@showProducts');
    Route::post('/rejection-accept-accountant', 'RejectionController@acceptRefusial');
    //
    Route::resource('/product-rejection-for-accountant', 'InsufficiencyController');
    Route::get('/product-rejection-for-accountant/products/{id}/{productId}', 'InsufficiencyController@showProducts');
    Route::post('/product-rejection-accept-accountant', 'InsufficiencyController@acceptRefusial');
    Route::resource('/log-rejections', 'LogRejectionsController');
});
/*
 * Messenger
 */
Route::group(['middleware' => 'auth:admin', 'namespace' => 'Admin\Chat'], function () {
    Route::get('/chats', 'ChatController@index')->name('chat.index');
    Route::get('/usersByUniqid/{id}', 'ChatController@usersByUniqid')->name('chat.usersByUniqid');
});
