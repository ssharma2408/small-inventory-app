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

        public function get_sales_person_orderreport(Request $request)
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
						/* $query = Order::select("orders.*")->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')->where('sales_manager_id', $user->id);
						if($request->customer != ""){
							$query->whereRaw(DB::raw("(customers.name like '%".$request->customer."%' OR customers.company_name like '%".$request->customer."%' OR customers.contact_name like '%".$request->customer."%')"));
						}
						if($request->start_date != "" && $request->end_date != ""){
							$query->where("orders.order_date", "<=", $request->end_date." 23:59:59");
							$query->where("orders.order_date", ">=", $request->start_date." 00:00:00");
						}
						$orders = $query->with(['sales_manager', 'customer', 'payment'])->get(); */
						
						$sql = 'SELECT orders.* FROM orders LEFT JOIN customers ON customers.id = orders.customer_id WHERE orders.deleted_at is NULL AND sales_manager_id = '.$user->id;
						
						if($request->customer != ""){
							$sql .= " AND (customers.name like '%".$request->customer."%' OR customers.company_name like '%".$request->customer."%' OR customers.contact_name like '%".$request->customer."%') ";
						}
						if($request->start_date != "" && $request->end_date != ""){
							$sql .= ' AND orders.order_date <= "'.$request->end_date.' 23:59:59"';
							$sql .= ' AND orders.order_date >= "'.$request->start_date.' 00:00:00"';
						}
						
						$orders = DB::select($sql);						
						
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
