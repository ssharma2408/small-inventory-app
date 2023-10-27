<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Inventory;
use App\Models\Order;

class ReportsController extends Controller
{
    public function get_expense_report(){
		
		$status = ['Due', 'Closed',  'Overdue'];
		
		$inventories = Inventory::with(['supplier', 'product', 'tax', 'media', 'payment'])->get();
		return view('admin.reports.expense_report', compact('inventories', 'status'));
	}
	
	public function get_order_report(){
		
		$status = ['Due', 'Closed',  'Overdue'];
		$orders = Order::with(['sales_manager', 'customer', 'payment'])->get();		
		
		return view('admin.reports.order_report', compact('orders', 'status'));
	}
}
