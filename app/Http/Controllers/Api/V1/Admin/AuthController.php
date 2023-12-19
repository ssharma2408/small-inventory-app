<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\TempInsuranceData;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $credentials = request(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        'Invalid credentials'
                    ],
                ]
            ], 422);
        }

		$menu_arr = ["user_management_access" => trans('cruds.userManagement.title'), "permission_access" => trans('cruds.permission.title'), "role_access" => trans('cruds.role.title'), "user_access" => trans('cruds.user.title'), "category_access" => trans('cruds.category.title'), "product_access" => trans('cruds.product.title'), "tax_access" => trans('cruds.tax.title'), "payment_method_access" => trans('cruds.paymentMethod.title'), "shrinkage_access" => trans('cruds.shrinkage.title'), "expense_management_access" => trans('cruds.expenseManagement.title'), "supplier_access" => trans('cruds.supplier.title'), "inventory_access" => trans('cruds.inventory.title'), "expense_payment_access" => trans('cruds.expensePayment.title'), "order_management_access" => trans('cruds.orderManagement.title'), "customer_access" => trans('cruds.customer.title'), "order_access" => trans('cruds.order.title'), "order_payment_access" => trans('cruds.orderPayment.title'), "credit_note_access" => trans('cruds.creditNote.title'), "profile_password_edit" => trans('global.change_password'), "report_access" => trans('reports.title')];

        $userdata = User::with('roles.permissions')->where('email', $request->email)->get();

		foreach($userdata as $key1 => $lelel1){
			foreach($lelel1['roles'] as $key2 => $level2){
				foreach($level2['permissions'] as $key3 => $level3){
					if(isset($menu_arr[$level3['title']])){
						$userdata[$key1]['roles'][$key2]['permissions'][$key3]['name'] = $menu_arr[$level3['title']];
					}
				}
			}
		}

        $user = User::where('email', $request->email)->first();
        $authToken = $user->createToken('auth-token')->plainTextToken;
        return response()->json([
            'access_token' => $authToken,
            'data'=> $userdata
        ], 200);
    }
}
