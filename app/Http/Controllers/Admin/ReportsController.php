<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Supplier;

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
}
