<?php
use App\Http\Controllers\Api\V1\Admin\AuthController;

Route::post('/v1/login', [AuthController::class, 'login']);
Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Dashboard
	Route::resource('dashboard', 'DashboardApiController');
	
	// User
    Route::apiResource('users', 'UserApiController');
	
	// Roles
	Route::resource('roles', 'RolesApiController');
	
	// Supplier
    Route::apiResource('suppliers', 'SupplierApiController');
	Route::get('suppliers/expenses/{id}', 'SupplierApiController@expenses')->name('suppliers.expenses');

    // Customers
    Route::post('upload-image','CustomersApiController@uploaddata');
	Route::get('revenue/{id}', 'CustomersApiController@revenue')->name('customers.revenue');
    Route::apiResource('customers', 'CustomersApiController');

    // Inventory
    Route::post('inventories/media', 'InventoryApiController@storeMedia')->name('inventories.storeMedia');
	Route::get('inventories/payment/{id?}', 'InventoryApiController@payment')->name('inventories.payment');
    Route::apiResource('inventories', 'InventoryApiController');

    // Payment Methods
    Route::apiResource('payment-methods', 'PaymentMethodApiController');

    // Orders
    Route::get('supplier-dashboard','OrdersApiController@getsupplierdash');
    Route::get('sales-manager','OrdersApiController@get_supplier');
	Route::get('orders/payment/{id?}', 'OrdersApiController@payment')->name('orders.payment');
    Route::apiResource('orders', 'OrdersApiController');

    Route::get('driver-dashboard', 'OrdersApiController@driver_dashboard');

    // Product
    Route::post('products/media', 'ProductApiController@storeMedia')->name('products.storeMedia');
    Route::apiResource('products', 'ProductApiController');
    Route::get('product-by-subcategory/{id}','ProductApiController@getproductbysubcategory');

    // Category
    Route::get('categories/{id}','CategoryApiController@index');
    Route::apiResource('categories', 'CategoryApiController');

    Route::get('product-category','CategoryApiController@getcategory');
    Route::get('product-subcategory/{id}','CategoryApiController@getsubcategory');

    // Tax
    Route::apiResource('taxes', 'TaxApiController');

    // Shrinkage
    Route::post('shrinkages/media', 'ShrinkageApiController@storeMedia')->name('shrinkages.storeMedia');
    Route::apiResource('shrinkages', 'ShrinkageApiController');

    // Order Payment
    Route::get('order-with-pending-amount','OrderPaymentApiController@getpendingamt');
    Route::apiResource('order-payments', 'OrderPaymentApiController');

    // Expense Payment
    Route::get('invoice-with-pending-amt/{id}','ExpensePaymentApiController@get_invoice_pending_amt');
    Route::get('get-invoices','ExpensePaymentApiController@get_invoice');
    Route::apiResource('expense-payments', 'ExpensePaymentApiController');
	
	// Credit Note
    Route::apiResource('credit-notes', 'CreditNoteApiController');
	
	// Cart
	Route::get('cart/{id}','CartApiController@index');
	Route::delete('cart/{cust_id}/{prod_id}','CartApiController@delete_cart_item');
	Route::delete('cart/{cust_id}','CartApiController@destroy');
    Route::apiResource('cart', 'CartApiController');
	
	// Reports
	Route::get('reports/get_expense_report', 'ReportsApiController@get_expense_report')->name('reports.get_expense_report');
	Route::get('reports/get_order_report', 'ReportsApiController@get_order_report')->name('reports.get_order_report');
	Route::get('reports/get_product_expiry_report', 'ReportsApiController@get_product_expiry_report')->name('reports.get_product_expiry_report');
	
	
    // Reports
	Route::get('get_sales_person_orderreport', 'ReportsApiController@get_sales_person_orderreport');


    // Profile
	Route::post('change_password','AuthController@change_password');
});

Route::controller(Api\V1\Admin\RegisterController::class)->group(function(){	
    Route::post('login', 'login');
});
