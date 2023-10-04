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
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
