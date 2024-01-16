<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Product;

use Symfony\Component\HttpFoundation\Response;

use DB;

class ReportsApiController extends Controller
{
        public function get_expense_report()
        {

                $status = ['Due', 'Closed',  'Overdue'];

                $inventories = Inventory::with(['supplier', 'media', 'payment'])->get();

                $suppliers = Supplier::select('supplier_name', 'id')->get();

                return response()->json([
                        'inventories' => $inventories,
                        'status' => $status,
                        'suppliers' => $suppliers
                ], 200);
        }

        public function get_order_report()
        {

                $status = ['Due', 'Closed',  'Overdue'];
                $orders = Order::with(['sales_manager', 'customer', 'payment'])->get();

                $customers = Customer::select('name', 'id')->get();

                return response()->json([
                        'orders' => $orders,
                        'status' => $status,
                        'customers' => $customers,
                ], 200);
        }

        public function get_sales_person_orderreport()
        {
                $status = [];
                $customers = [];
                $orders = [];
                $user = \Auth::user();
                $status_code = 401;
                $role = $user->roles()->first()->toArray();
                if ($role['title'] == 'Sales Manager') {
                        $status_code = 200;
                        $status = ['Due', 'Closed',  'Overdue'];
                        $customers = Customer::select('name', 'id')->get();
			$orders = Order::where('sales_manager_id',$user->id)->with(['sales_manager', 'customer', 'payment'])->get();
		}
                return response()->json([
                        'orders' => $orders,
                        'status' => $status,
                        'customers' => $customers,
                ], $status_code);
        }

        public function get_product_expiry_report()
        {

                $status = ['Due', 'Closed',  'Overdue'];
                $expense_items = DB::table('expense_items')
                        ->join('products', 'expense_items.product_id', '=', 'products.id')
                        ->join('inventories', 'expense_items.expense_id', '=', 'inventories.id')
                        ->select('products.name', 'expense_items.product_id', 'expense_items.stock', 'expense_items.is_box', 'expense_items.purchase_price',  'products.box_size', 'expense_items.exp_date', 'inventories.invoice_number', 'inventories.id')
                        ->get();

                $products = Product::select('name', 'id')->get();

                return response()->json([
                        'expense_items' => $expense_items,
                        'status' => $status,
                        'products' => $products
                ], 200);
        }
}
