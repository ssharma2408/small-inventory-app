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

    // Customers
    Route::post('upload-image','CustomersApiController@uploaddata');
	Route::get('revenue/{id}', 'CustomersApiController@revenue')->name('customers.revenue');
    Route::apiResource('customers', 'CustomersApiController');

    // Inventory
    Route::post('inventories/media', 'InventoryApiController@storeMedia')->name('inventories.storeMedia');
    Route::apiResource('inventories', 'InventoryApiController');

    // Payment Methods
    Route::apiResource('payment-methods', 'PaymentMethodApiController');

    // Orders
    Route::get('sales-manager','OrdersApiController@get_supplier');
    Route::apiResource('orders', 'OrdersApiController');

    // Product
    Route::post('products/media', 'ProductApiController@storeMedia')->name('products.storeMedia');
    Route::apiResource('products', 'ProductApiController');
    Route::get('product-by-category/{id}','ProductApiController@getproductbycategory');

    // Category
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
	
	// Reports
	Route::get('reports/get_expense_report', 'ReportsApiController@get_expense_report')->name('reports.get_expense_report');
	Route::get('reports/get_order_report', 'ReportsApiController@get_order_report')->name('reports.get_order_report');
	Route::get('reports/get_product_expiry_report', 'ReportsApiController@get_product_expiry_report')->name('reports.get_product_expiry_report');
	
	// Profile
	Route::post('change_password','AuthController@change_password');
});

Route::controller(Api\V1\Admin\RegisterController::class)->group(function(){	
    Route::post('login', 'login');
});
