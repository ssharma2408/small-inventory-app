<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Supplier
    Route::apiResource('suppliers', 'SupplierApiController');

    // Customers
    Route::apiResource('customers', 'CustomersApiController');

    // Inventory
    Route::apiResource('inventories', 'InventoryApiController');

    // Orders
    Route::apiResource('orders', 'OrdersApiController');

    // Order Items
    Route::apiResource('order-items', 'OrderItemsApiController');
});
