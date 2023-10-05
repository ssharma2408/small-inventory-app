<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\OrderItem;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use DB;
use Auth;

class OrdersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$user = \Auth::user();
        $role=$user->roles()->first()->toArray();		

		$is_admin = false;

		if($role['title'] == 'Admin'){
			$is_admin = true;
		}

		$orders = Order::with(['sales_manager', 'customer'])->get();

        return view('admin.orders.index', compact('orders', 'is_admin'));
    }

    public function create()
    {
        abort_if(Gate::denies('order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$user = \Auth::user();
        $role=$user->roles()->first()->toArray();		

		if($role['title'] == 'Sales Manager'){
			$sales_managers = User::whereHas(
								'roles', function($q){
									$q->where('title', 'Sales Manager');
								}
							)->where('id', $user->id)->pluck('name', 'id');
		}else{
			$sales_managers = User::whereHas(
								'roles', function($q){
									$q->where('title', 'Sales Manager');
								}
							)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
		}

        $customers = Customer::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
		
		$products = Product::select('id', 'name')->get();

        return view('admin.orders.create', compact('customers', 'sales_managers', 'products'));
    }

    public function store(StoreOrderRequest $request)
    {
        $params = 	$request->all();

		$params['extra_discount'] = ($params['extra_discount'] == null) ? 0.00 : $params['extra_discount'];

		$order = Order::create($params);

		$data = [];
		for($i=0; $i < count($request['item_name']); $i++){			
			if(!empty($request['item_name']) && !empty($request['item_quantity'])){
				$item = [];
				$item['product_id'] = $request['item_name'][$i];
				$item['order_id'] = $order->id;
				$item['quantity'] = $request['item_quantity'][$i];			
				$data[] = $item;
			}

		}

		if(!empty($data)){
			OrderItem::insert($data);
		}

        return redirect()->route('admin.orders.index');
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = \Auth::user();
        $role=$user->roles()->first()->toArray();		

		if($role['title'] == 'Admin' || $order->sales_manager_id === \Auth::user()->id){

			if($role['title'] == 'Sales Manager'){
				$sales_managers = User::whereHas(
									'roles', function($q){
										$q->where('title', 'Sales Manager');
									}
								)->where('id', $user->id)->pluck('name', 'id');
			}else{
				$sales_managers = User::whereHas(
									'roles', function($q){
										$q->where('title', 'Sales Manager');
									}
								)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
			}

			$customers = Customer::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

			$products = Product::select('id', 'name')->get();

			$order_items =DB::table('order_items')
					->join('products','order_items.product_id', '=', 'products.id')               
					->select('order_items.product_id','order_items.quantity','products.stock', 'products.selling_price')
					->where('order_items.order_id', $order->id)
					->get();

			$order->load('sales_manager', 'customer');

			return view('admin.orders.edit', compact('customers', 'order', 'sales_managers', 'products', 'order_items'));
		}else{
			return redirect()->route('admin.orders.index')->withErrors('You are not authorized to perform this action');
		}
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $params = 	$request->all();

		$params['extra_discount'] = ($params['extra_discount'] == null) ? 0.00 : $params['extra_discount'];

		$order->update($params);

		DB::table('order_items')->where('order_id', $order->id)->delete();

		$data = [];
		for($i=0; $i < count($request['item_name']); $i++){			
			if(!empty($request['item_name']) && !empty($request['item_quantity'])){
				$item = [];
				$item['product_id'] = $request['item_name'][$i];
				$item['order_id'] = $order->id;
				$item['quantity'] = $request['item_quantity'][$i];			
				$data[] = $item;
			}

		}

		if(!empty($data)){
			OrderItem::insert($data);
		}

        return redirect()->route('admin.orders.index');
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load('sales_manager', 'customer');

        return view('admin.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$user = \Auth::user();
        $role=$user->roles()->first()->toArray();		

		if($role['title'] == 'Admin' || $order->sales_manager_id === \Auth::user()->id){

			$order->delete();
		}else{
			return redirect()->route('admin.orders.index')->withErrors('You are not authorized to perform this action');
		}

        return back();
    }

    public function massDestroy(MassDestroyOrderRequest $request)
    {
        $orders = Order::find(request('ids'));

        foreach ($orders as $order) {
            $order->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function get_product_detail($id){
		$product = Product::select('id', 'name', 'stock', 'selling_price')->where('id', $id)->first();

		return response()->json(array('success'=>1, 'product'=>$product), 200);
	}
}
