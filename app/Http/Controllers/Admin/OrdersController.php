<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderPaymentMaster;
use App\Models\OrderPayment;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Tax;
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
		
		$order_id_arr = $this->get_payments();

        return view('admin.orders.index', compact('orders', 'is_admin', 'order_id_arr'));
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
		
		$categories = $this->buildTree(Category::select('name', 'id', 'category_id')->get()->toArray());
		
		$taxes = Tax::select('title', 'id')->get();

        return view('admin.orders.create', compact('customers', 'sales_managers', 'categories', 'taxes'));
    }

    public function store(StoreOrderRequest $request)
    {
		$order_pay_detail = [];
		$params = 	$request->all();		

		$params['extra_discount'] = ($params['extra_discount'] == null) ? 0.00 : $params['extra_discount'];
		$params['delivery_agent_id'] = null;

		$order = Order::create($params);

		$data = [];
		for($i=0; $i < count($request['item_name']); $i++){			
			if(!empty($request['item_name']) && !empty($request['item_quantity'])){
				$item = [];
				$item['product_id'] = $request['item_name'][$i];
				$item['order_id'] = $order->id;
				$item['quantity'] = $request['item_quantity'][$i];
				$item['category_id'] = $request['item_category'][$i];
				$item['sale_price'] = $request['item_sale_priec'][$i];
				$item['tax_id'] = $request['item_tax_id'][$i];
				$item['is_box'] = isset($request['is_box'][$i]) ? 1 : 0;
				$data[] = $item;
			}

		}

		if(!empty($data)){
			OrderItem::insert($data);
		}
		
		$order_pay_detail['customer_id'] = $params['customer_id'];		
		$order_pay_detail['order_total'] = $params['order_total'];
		$order_pay_detail['order_paid'] = 0;
		$order_pay_detail['order_pending'] = $params['order_total'];
		$order_pay_detail['payment_status'] = 0;
		$order_pay_detail['order_number'] = $order->id;
		
		OrderPaymentMaster::create($order_pay_detail);

        return redirect()->route('admin.orders.index');
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = \Auth::user();
        $role=$user->roles()->first()->toArray();

		if($role['title'] == 'Admin' || $order->sales_manager_id === \Auth::user()->id ||  $order->delivery_agent_id === \Auth::user()->id){

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
			
			$delivery_agents = User::whereHas(
									'roles', function($q){
										$q->where('title', 'Delivery Agent');
									}
								)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

			$customers = Customer::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

			$categories = $this->buildTree(Category::select('name', 'id', 'category_id')->get()->toArray());

			$order_items = DB::table('order_items')
					->join('products','order_items.product_id', '=', 'products.id')
					->join('categories','order_items.category_id', '=', 'categories.id')
					->join('taxes','taxes.id', '=', 'order_items.tax_id')
					->select('categories.name as category_name', 'categories.id as category_id', 'products.name', 'order_items.product_id','order_items.quantity','products.stock', 'products.selling_price', 'products.maximum_selling_price', 'order_items.is_box', 'order_items.sale_price', 'order_items.tax_id', 'products.box_size', 'taxes.tax')
					->where('order_items.order_id', $order->id)
					->get();

			$order->load('sales_manager', 'customer');
			
			$taxes = Tax::select('title', 'id')->get();

			return view('admin.orders.edit', compact('customers', 'order', 'sales_managers', 'categories', 'order_items', 'delivery_agents', 'taxes'));
		}else{
			return redirect()->route('admin.orders.index')->withErrors('You are not authorized to perform this action');
		}
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $params = 	$request->all();

		$params['extra_discount'] = ($params['extra_discount'] == null) ? 0.00 : $params['extra_discount'];
		$params['delivery_agent_id'] = ($params['status'] == 4) ? $params['delivery_agent_id'] : null;

		$order->update($params);		

		DB::table('order_items')->where('order_id', $order->id)->delete();

		$data = [];
		for($i=0; $i < count($request['item_name']); $i++){
			if(!empty($request['item_name']) && !empty($request['item_quantity'])){
				$item = [];
				$item['product_id'] = $request['item_name'][$i];
				$item['order_id'] = $order->id;
				$item['quantity'] = $request['item_quantity'][$i];
				$item['category_id'] = $request['item_category'][$i];
				$item['sale_price'] = $request['item_sale_priec'][$i];
				$item['tax_id'] = $request['item_tax_id'][$i];
				$item['is_box'] = isset($request['is_box'][$i]) ? 1 : 0;
				$data[] = $item;
			}

		}

		if(!empty($data)){
			OrderItem::insert($data);
		}
		
		//If order is accepted by Admin then decrease the stock
		if($params['status'] == 4){
			foreach($data as $ord_item){
				$product = Product::find($ord_item['product_id']);
				$product->decrement('stock', $ord_item['quantity']);
			}
		}
		
		if(($order->order_total != $request->order_total)){
			OrderPaymentMaster::where('order_id', $order->id)
				   ->update([
					   'order_total' => $request->order_total,
					   'order_pending' => $request->order_total
					]);
		}

        return redirect()->route('admin.orders.index');
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$user = \Auth::user();
        $role=$user->roles()->first()->toArray();		

        $order = $order->load('sales_manager', 'customer');		
		
		$order['order_item'] = DB::table('order_items')
					->join('products','order_items.product_id', '=', 'products.id')
					->join('categories','order_items.category_id', '=', 'categories.id')
					->select('categories.name as category_name', 'categories.id as category_id','order_items.quantity','products.stock', 'products.selling_price', 'products.name')
					->where('order_items.order_id', $order->id)
					->get()->toArray();	

        return view('admin.orders.show', compact('order', 'role'));
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
		$product = Product::select('id', 'name', 'stock', 'selling_price', 'maximum_selling_price', 'box_size', 'tax_id')->where('id', $id)->first();

		return response()->json(array('success'=>1, 'product'=>$product), 200);
	}
	
	public function complete_order(Request $request){
		if($request->signature == ""){
			return false;
		}
		
		Order::where('id', $request->id)
       ->update([
           'status' => 1,
		   'customer_sign' => $request->signature
        ]);
		
		return back();
	}
	
	public function buildTree(array $elements, $parentId = ""){
		$branch = array();

		foreach ($elements as $elementobj) {
			$element = (array) $elementobj;
			if ($element['category_id'] == $parentId) {
				$children = $this->buildTree($elements, $element['id']);
				if ($children) {
					$element['children'] = $children;
				}
				$branch[] = $element;
			}
		}

		return $branch;
	}
	
	private function get_payments(){
		
		$order_id_arr = [];
		
		$order_ids = OrderPayment::get('order_id')->toArray();
		
		foreach($order_ids as $id){
			$order_id_arr[] = $id['order_id'];
		}
		$order_id_arr = array_unique($order_id_arr);
		
		return $order_id_arr;
	}
	
	public function payment($order_id=""){		
		
			$status_arr = array("Unpaid", "Paid", "Partial Paid");
			
			$payment_arr = [];			
			$payment_query = DB::table('order_payments')
				->select('order_payments.amount', 'order_payments.description', 'order_payments.date', 'order_payment_master.order_number', 'order_payment_master.order_total', 'order_payment_master.order_paid', 'order_payment_master.order_pending', 'order_payment_master.payment_status', 'customers.name as cust_name', 'customers.phone_number', 'customers.email', 'payment_methods.name')
				->leftJoin('order_payment_master','order_payment_master.order_number','=','order_payments.order_id')
				->join('customers','customers.id','=','order_payment_master.customer_id')
				->join('payment_methods','payment_methods.id','=','order_payments.payment_id');
				
			if($order_id != ""){
				$payment_query->where('order_payment_master.order_number','=',$order_id);
			}
			
			$payment_details = $payment_query->get()->toArray();
				
			foreach($payment_details as $detail){
				
				$payment_arr[$detail->order_number] = array('order_number'=>$detail->order_number, 'order_total'=>$detail->order_total, 'order_paid'=>$detail->order_paid, 'order_pending'=>$detail->order_pending, 'payment_status'=>$status_arr[$detail->payment_status], 'cust_name'=>$detail->cust_name, 'phone_number'=>$detail->phone_number, 'email'=>$detail->email, 'payment_detail'=>[]);				
			}

			foreach($payment_details as $detail){
				
				$payment_arr[$detail->order_number]['payment_detail'][] = array('amount'=>$detail->amount, 'description'=>$detail->description, 'date'=>$detail->date, 'name'=>$detail->name);				
			}	
		
		return view('admin.orders.payment_history', compact('payment_arr'));
		
	}

}
