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

class HomeController
{
    public function index()
    {
       	$category = [];
		$category['total'] = Category::count();
		$category['latest'] = Category::select('name')->orderBy('id','DESC')->take(5)->get()->toArray();
		
		$product = [];
		$product['total'] = Product::count();
		$product['latest'] = Product::select('name')->orderBy('id','DESC')->take(5)->get()->toArray();
		
		$tax = [];
		$tax['total'] = Tax::count();
		$tax['latest'] = Tax::select('title')->orderBy('id','DESC')->take(5)->get()->toArray();
		
		$payment_method = [];
		$payment_method['total'] = PaymentMethod::count();
		$payment_method['latest'] = PaymentMethod::select('name')->orderBy('id','DESC')->take(5)->get()->toArray();
		
		$shrinkage = [];
		$shrinkage['total'] = Shrinkage::count();
		$shrinkage['latest'] = Shrinkage::select('date')->orderBy('id','DESC')->take(5)->get()->toArray();
		
		$supplier = [];
		$supplier['total'] = Supplier::count();
		$supplier['latest'] = Supplier::select('supplier_name')->orderBy('id','DESC')->take(5)->get()->toArray();
		
		$expense = [];
		$expense['total'] = Inventory::count();
		$expense['latest'] = Inventory::select('invoice_number')->orderBy('id','DESC')->take(5)->get()->toArray();
		
		$customer = [];
		$customer['total'] = Customer::count();
		$customer['latest'] = Customer::select('name')->orderBy('id','DESC')->take(5)->get()->toArray();
		
		$order = [];
		$order['total'] = Order::count();
		$order['latest'] = Order::select('created_at')->orderBy('id','DESC')->take(5)->get()->toArray();
		
		return view('home', compact('category', 'product', 'tax', 'payment_method', 'shrinkage', 'supplier', 'expense', 'customer', 'order'));
    }
}
