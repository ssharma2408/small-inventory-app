	<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Supplier
    Route::delete('suppliers/destroy', 'SupplierController@massDestroy')->name('suppliers.massDestroy');
    Route::resource('suppliers', 'SupplierController');

    // Customers
    Route::delete('customers/destroy', 'CustomersController@massDestroy')->name('customers.massDestroy');
    Route::resource('customers', 'CustomersController');

    // Inventory
    Route::delete('inventories/destroy', 'InventoryController@massDestroy')->name('inventories.massDestroy');
	Route::get('inventories/get_products/{id}', 'InventoryController@get_products')->name('inventories.get_products');	
    Route::post('inventories/media', 'InventoryController@storeMedia')->name('inventories.storeMedia');
    Route::post('inventories/ckmedia', 'InventoryController@storeCKEditorImages')->name('inventories.storeCKEditorImages');
	Route::get('inventories/payment/{$id}', 'InventoryController@payment')->name('inventories.payment');
    Route::resource('inventories', 'InventoryController');

    // Orders
    Route::delete('orders/destroy', 'OrdersController@massDestroy')->name('orders.massDestroy');
	Route::get('orders/get_product_detail/{id}', 'OrdersController@get_product_detail')->name('orders.get_product_detail');
	Route::post('orders/complete_order', 'OrdersController@complete_order')->name('orders.complete');
    Route::resource('orders', 'OrdersController');

    // Product
    Route::delete('products/destroy', 'ProductController@massDestroy')->name('products.massDestroy');
	Route::get('products/get_drod_detail/{id}', 'ProductController@get_drod_detail')->name('products.get_drod_detail');
    Route::post('products/media', 'ProductController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductController@storeCKEditorImages')->name('products.storeCKEditorImages');
    Route::resource('products', 'ProductController');

    // Category
    Route::delete('categories/destroy', 'CategoryController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoryController');

    // Tax
    Route::delete('taxes/destroy', 'TaxController@massDestroy')->name('taxes.massDestroy');
	Route::get('taxes/get_tax/{id}', 'TaxController@get_tax')->name('taxes.get_tax');
    Route::resource('taxes', 'TaxController');

    // Shrinkage
    Route::delete('shrinkages/destroy', 'ShrinkageController@massDestroy')->name('shrinkages.massDestroy');
    Route::post('shrinkages/media', 'ShrinkageController@storeMedia')->name('shrinkages.storeMedia');
    Route::post('shrinkages/ckmedia', 'ShrinkageController@storeCKEditorImages')->name('shrinkages.storeCKEditorImages');
    Route::resource('shrinkages', 'ShrinkageController');

    // Order Payment
    Route::delete('order-payments/destroy', 'OrderPaymentController@massDestroy')->name('order-payments.massDestroy');
    Route::resource('order-payments', 'OrderPaymentController');

    // Payment Method
    Route::delete('payment-methods/destroy', 'PaymentMethodController@massDestroy')->name('payment-methods.massDestroy');
    Route::resource('payment-methods', 'PaymentMethodController');

    // Expense Payment
    Route::delete('expense-payments/destroy', 'ExpensePaymentController@massDestroy')->name('expense-payments.massDestroy');
    Route::resource('expense-payments', 'ExpensePaymentController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
