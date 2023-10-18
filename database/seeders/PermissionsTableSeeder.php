<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'supplier_create',
            ],
            [
                'id'    => 18,
                'title' => 'supplier_edit',
            ],
            [
                'id'    => 19,
                'title' => 'supplier_show',
            ],
            [
                'id'    => 20,
                'title' => 'supplier_delete',
            ],
            [
                'id'    => 21,
                'title' => 'supplier_access',
            ],
            [
                'id'    => 22,
                'title' => 'customer_create',
            ],
            [
                'id'    => 23,
                'title' => 'customer_edit',
            ],
            [
                'id'    => 24,
                'title' => 'customer_show',
            ],
            [
                'id'    => 25,
                'title' => 'customer_delete',
            ],
            [
                'id'    => 26,
                'title' => 'customer_access',
            ],
            [
                'id'    => 27,
                'title' => 'inventory_create',
            ],
            [
                'id'    => 28,
                'title' => 'inventory_edit',
            ],
            [
                'id'    => 29,
                'title' => 'inventory_show',
            ],
            [
                'id'    => 30,
                'title' => 'inventory_delete',
            ],
            [
                'id'    => 31,
                'title' => 'inventory_access',
            ],
            [
                'id'    => 32,
                'title' => 'order_create',
            ],
            [
                'id'    => 33,
                'title' => 'order_edit',
            ],
            [
                'id'    => 34,
                'title' => 'order_show',
            ],
            [
                'id'    => 35,
                'title' => 'order_delete',
            ],
            [
                'id'    => 36,
                'title' => 'order_access',
            ],
            [
                'id'    => 37,
                'title' => 'product_create',
            ],
            [
                'id'    => 38,
                'title' => 'product_edit',
            ],
            [
                'id'    => 39,
                'title' => 'product_show',
            ],
            [
                'id'    => 40,
                'title' => 'product_delete',
            ],
            [
                'id'    => 41,
                'title' => 'product_access',
            ],
            [
                'id'    => 42,
                'title' => 'category_create',
            ],
            [
                'id'    => 43,
                'title' => 'category_edit',
            ],
            [
                'id'    => 44,
                'title' => 'category_show',
            ],
            [
                'id'    => 45,
                'title' => 'category_delete',
            ],
            [
                'id'    => 46,
                'title' => 'category_access',
            ],
            [
                'id'    => 47,
                'title' => 'tax_create',
            ],
            [
                'id'    => 48,
                'title' => 'tax_edit',
            ],
            [
                'id'    => 49,
                'title' => 'tax_show',
            ],
            [
                'id'    => 50,
                'title' => 'tax_delete',
            ],
            [
                'id'    => 51,
                'title' => 'tax_access',
            ],
            [
                'id'    => 52,
                'title' => 'shrinkage_create',
            ],
            [
                'id'    => 53,
                'title' => 'shrinkage_edit',
            ],
            [
                'id'    => 54,
                'title' => 'shrinkage_show',
            ],
            [
                'id'    => 55,
                'title' => 'shrinkage_delete',
            ],
            [
                'id'    => 56,
                'title' => 'shrinkage_access',
            ],
            [
                'id'    => 57,
                'title' => 'order_payment_create',
            ],
            [
                'id'    => 58,
                'title' => 'order_payment_edit',
            ],
            [
                'id'    => 59,
                'title' => 'order_payment_show',
            ],
            [
                'id'    => 60,
                'title' => 'order_payment_delete',
            ],
            [
                'id'    => 61,
                'title' => 'order_payment_access',
            ],
            [
                'id'    => 62,
                'title' => 'payment_method_create',
            ],
            [
                'id'    => 63,
                'title' => 'payment_method_edit',
            ],
            [
                'id'    => 64,
                'title' => 'payment_method_show',
            ],
            [
                'id'    => 65,
                'title' => 'payment_method_delete',
            ],
            [
                'id'    => 66,
                'title' => 'payment_method_access',
            ],
            [
                'id'    => 67,
                'title' => 'expense_payment_create',
            ],
            [
                'id'    => 68,
                'title' => 'expense_payment_edit',
            ],
            [
                'id'    => 69,
                'title' => 'expense_payment_show',
            ],
            [
                'id'    => 70,
                'title' => 'expense_payment_delete',
            ],
            [
                'id'    => 71,
                'title' => 'expense_payment_access',
            ],
			[
                'id'    => 72,
                'title' => 'expense_management_access',
            ],
			[
                'id'    => 73,
                'title' => 'order_management_access',
            ],
			[
                'id'    => 74,
                'title' => 'expense_history_access',
            ],
			[
                'id'    => 75,
                'title' => 'order_history_access',
            ],
            [
                'id'    => 76,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
