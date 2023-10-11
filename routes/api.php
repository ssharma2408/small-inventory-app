<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Supplier
    Route::apiResource('suppliers', 'SupplierApiController');

    // Customers
    Route::apiResource('customers', 'CustomersApiController');

    // Inventory
    Route::post('inventories/media', 'InventoryApiController@storeMedia')->name('inventories.storeMedia');
    Route::apiResource('inventories', 'InventoryApiController');

    // Orders
    Route::apiResource('orders', 'OrdersApiController');

    // Product
    Route::apiResource('products', 'ProductApiController');

    // Category
    Route::apiResource('categories', 'CategoryApiController');

    // Tax
    Route::apiResource('taxes', 'TaxApiController');

    // Shrinkage
    Route::post('shrinkages/media', 'ShrinkageApiController@storeMedia')->name('shrinkages.storeMedia');
    Route::apiResource('shrinkages', 'ShrinkageApiController');

    // Order Payment
    Route::apiResource('order-payments', 'OrderPaymentApiController');

    // Expense Payment
    Route::apiResource('expense-payments', 'ExpensePaymentApiController');
});

Route::controller(Api\V1\Admin\RegisterController::class)->group(function(){	
    Route::post('login', 'login');
});
