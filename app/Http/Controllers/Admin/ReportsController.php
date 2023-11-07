<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\Product;

use DB;

class ReportsController extends Controller
{
    public function get_expense_report(){
		
		$status = ['Due', 'Closed',  'Overdue'];
		
		$inventories = Inventory::with(['supplier', 'media', 'payment'])->get();
		$suppliers = Supplier::select('supplier_name', 'id')->get();
		return view('admin.reports.expense_report', compact('inventories', 'status', 'suppliers'));
	}
	
	public function get_order_report(){
		
		$status = ['Due', 'Closed',  'Overdue'];
		$orders = Order::with(['sales_manager', 'customer', 'payment'])->get();
		
		$customers = Customer::select('name', 'id')->get();
		
		return view('admin.reports.order_report', compact('orders', 'status', 'customers'));
	}
	
	public function get_product_expiry_report(){
		
		$status = ['Due', 'Closed',  'Overdue'];
		$expense_items = DB::table('expense_items')
                ->join('products', 'expense_items.product_id', '=', 'products.id')
                ->join('inventories', 'expense_items.expense_id', '=', 'inventories.id')
                ->select('products.name', 'expense_items.product_id', 'expense_items.stock', 'expense_items.is_box', 'expense_items.purchase_price',  'products.box_size', 'expense_items.exp_date', 'inventories.invoice_number', 'inventories.id')
                ->get();
		
		$products = Product::select('name', 'id')->get();
		
		return view('admin.reports.product_expiry_report', compact('expense_items', 'status', 'products'));
	}
}
