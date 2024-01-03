<?php

namespace App\Http\Controllers\Admin;
use DB;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tax;
use App\Models\PaymentMethod;
use App\Models\Shrinkage;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderPaymentMaster;

class HomeController
{
    public function index()
    {
       	$category = [];
		$category['total'] = Category::count();		
		
		$product = [];
		$product['total'] = Product::count();		
		
		$tax = [];
		$tax['total'] = Tax::count();		
		
		$payment_method = [];
		$payment_method['total'] = PaymentMethod::count();		
		
		$shrinkage = [];
		$shrinkage['total'] = Shrinkage::count();		
		
		$supplier = [];
		$supplier['total'] = Supplier::count();		
		
		$expense = [];
		$expense['total'] = Inventory::count();		
		
		$customer = [];
		$customer['total'] = Customer::count();		
		
		$order = [];
		$order['total'] = Order::count();
		$order['latest'] = Order::select('created_at')->orderBy('id','DESC')->take(5)->get()->toArray();
		
		$admin = [];
		//$admin['total_open_order'] = OrderPaymentMaster::whereIn('payment_status', array(0, 2))->count();
		$admin['total_open_order'] = DB::table('order_payment_master')->select('orders.id')->join('orders', 'order_payment_master.order_number', '=', 'orders.id')->where('orders.deleted_at', '=', null)->whereIn('order_payment_master.payment_status', array(0, 2))->get()->count();
		$admin['total_order'] = Order::count();
		$admin['accepted_order'] = Order::select('id', 'order_total')->where('status', '4')->orderBy('id','DESC')->take(5)->get()->toArray();
		$admin['expenses'] = Inventory::select('invoice_number', 'final_price')->orderBy('id','DESC')->take(5)->get()->toArray();
		$admin['total_expenses'] = Inventory::count();
		
		$sales = [];		
		$sales['accepted_order'] = Order::select('id', 'order_total')->where('status', '4')->where('sales_manager_id', \Auth::user()->id)->orderBy('id','DESC')->take(5)->get()->toArray();
		
		$del_agent = [];		
		$del_agent['total_assigned_orders'] = Order::where('status', '4')->where('delivery_agent_id', \Auth::user()->id)->count();
		
		return view('home', compact('category', 'product', 'tax', 'payment_method', 'shrinkage', 'supplier', 'expense', 'customer', 'order', 'admin', 'sales', 'del_agent'));
    }
}
