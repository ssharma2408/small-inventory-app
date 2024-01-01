<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\Admin\OrderResource;
use App\Models\User;
use App\Models\Customer;
use App\Models\CreditNote;
use App\Models\CreditNoteLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\OrderPaymentMaster;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class OrdersApiController extends Controller
{

	public function get_supplier()
	{
		$user = \Auth::user();
		$role = $user->roles()->first()->toArray();

		if ($role['title'] == 'Sales Manager') {
			$sales_managers = User::whereHas(
				'roles',
				function ($q) {
					$q->where('title', 'Sales Manager');
				}
			)->where('id', $user->id)->get();
		} else {
			$sales_managers = User::whereHas(
				'roles',
				function ($q) {
					$q->where('title', 'Sales Manager');
				}
			)->get();
		}

		return (new OrderResource($sales_managers))
			->response()
			->setStatusCode(Response::HTTP_CREATED);
	}
	
	public function index()
	{
		//abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		$order_id_arr = $this->get_payments();
		$ord_arr = [];
		$order = Order::with(['sales_manager', 'customer'])->get();

		foreach ($order as $rw) {
			$rw['edit_key'] = in_array($rw->id, $order_id_arr) ? 1 : 0;
			array_push($ord_arr, $rw);
		}

		return new OrderResource($ord_arr);
	}

	public function store(StoreOrderRequest $request)
	{
		$order_pay_detail = [];
        $params = $request->all();
        $due_date_arr = Customer::PAYMENT_TERMS_SELECT;

        $due_days = Customer::select('payment_terms')->where('id', $request->customer_id)->first()->toArray();

        $params['extra_discount'] = ($params['extra_discount'] == null) ? 0.00 : $params['extra_discount'];
        $params['delivery_agent_id'] = null;
        $params['due_date'] = date('Y-m-d H:i:s', strtotime($request->order_date . ' + ' . explode(" ", $due_date_arr[$due_days['payment_terms']])[0] . ' days'));

        $order = Order::create($params);

        $data = [];
        for ($i = 0; $i < count($request['item_name']); $i++) {
            if (!empty($request['item_name']) && !empty($request['item_quantity'])) {
                $item = [];
                $item['product_id'] = $request['item_name'][$i];
                $item['order_id'] = $order->id;
                $item['quantity'] = $request['item_quantity'][$i];
                $item['category_id'] = $request['item_category'][$i];
                $item['sub_category_id'] = $request['item_subcategory'][$i];
                $item['sale_price'] = $request['item_sale_priec'][$i];
                $item['tax_id'] = $request['item_tax_id'][$i];
                $item['is_box'] = $request['is_box'][$i];
                $data[] = $item;
            }

        }

        if (!empty($data)) {
            OrderItem::insert($data);
        }
		
		// Credit Note
		if(isset($request->use_credit)){
			$customer = Customer::find($params['customer_id']);
			$customer->decrement('credit_note_balance', $params['credit_balance_value']);
			
			$credit_notes = CreditNote::where('customer_id', $params['customer_id'])->get();
			CreditNote::where('customer_id', $params['customer_id'])
				   ->update([
					   'deleted_at' => date('Y-m-d H:i:s')
					]);
			$credit_log_data = [];
			$credit_balance_value = $params['credit_balance_value'];
			foreach($credit_notes as $cn){
				
				$balance = $credit_balance_value - $cn->amount;
				
				$item = [];
				$item['credit_order_id'] = $cn->order_id;
				$item['debit_order_id'] = $order->id;
				$item['customer_id'] = $params['customer_id'];
				$item['amount'] = $cn->amount;
				$item['balance'] = $balance;
				
				$credit_balance_value = $balance;
				$credit_log_data[] = $item;
			}
			
			CreditNoteLog::insert($credit_log_data);
		}

        $order_pay_detail['customer_id'] = $params['customer_id'];
        $order_pay_detail['order_total'] = $params['order_total'];
        $order_pay_detail['order_paid'] = 0;
        $order_pay_detail['order_pending'] = $params['order_total'];
        $order_pay_detail['payment_status'] = 0;
        $order_pay_detail['order_number'] = $order->id;

        OrderPaymentMaster::create($order_pay_detail);

		return (new OrderResource($order))
			->response()
			->setStatusCode(Response::HTTP_CREATED);
	}

	public function show(Order $order)
	{
		//abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		/*$order_items = OrderItem::where('order_id', $order->id)->get();

		$item_arr = [];

		foreach ($order_items as $item) {
			$item_arr[] = $item;
		}
		$order_details = $order->load(['sales_manager', 'customer']);

		$order_details['order_items'] = $item_arr;*/

		$order = $order->load('sales_manager', 'customer');

		$order['order_item'] = DB::table('order_items')
			->join('products', 'order_items.product_id', '=', 'products.id')
			->join('categories', 'order_items.category_id', '=', 'categories.id')
			->join('categories as c', 'order_items.sub_category_id', '=', 'c.id')
            ->join('taxes', 'taxes.id', '=', 'order_items.tax_id')
			 ->select('c.name as sub_category_name', 'c.id as sub_category_id', 'categories.name as category_name', 'categories.id as category_id', 'order_items.quantity', 'products.stock', 'products.selling_price', 'products.name', 'products.maximum_selling_price', 'order_items.is_box', 'order_items.sale_price', 'order_items.tax_id', 'products.box_size', 'taxes.title', 'taxes.tax')
			->where('order_items.order_id', $order->id)
			->get()->toArray();

		return new OrderResource($order);
	}

	public function update(UpdateOrderRequest $request, Order $order)
	{
		$params = $request->all();

        $params['extra_discount'] = ($params['extra_discount'] == null) ? 0.00 : $params['extra_discount'];
        $params['delivery_agent_id'] = ($params['status'] == 4) ? $params['delivery_agent_id'] : null;

        $order->update($params);

        DB::table('order_items')->where('order_id', $order->id)->delete();

        $data = [];
        for ($i = 0; $i < count($request['item_name']); $i++) {
            if (!empty($request['item_name']) && !empty($request['item_quantity'])) {
                $item = [];
                $item['product_id'] = $request['item_name'][$i];
                $item['order_id'] = $order->id;
                $item['quantity'] = $request['item_quantity'][$i];
                $item['category_id'] = $request['item_category'][$i];
                $item['sub_category_id'] = $request['item_subcategory'][$i];
                $item['sale_price'] = $request['item_sale_priec'][$i];
                $item['tax_id'] = $request['item_tax_id'][$i];
                $item['is_box'] = $request['is_box'][$i];
                $data[] = $item;
            }

        }

        if (!empty($data)) {
            OrderItem::insert($data);
        }

        //If order is accepted by Admin then decrease the stock
        if ($params['status'] == 4) {
            foreach ($data as $ord_item) {
                $product = Product::find($ord_item['product_id']);
                $product->decrement('stock', $ord_item['quantity']);
            }
        }

        if (($order->order_total != $request->order_total)) {
            OrderPaymentMaster::where('order_id', $order->id)
                ->update([
                    'order_total' => $request->order_total,
                    'order_pending' => $request->order_total,
                ]);
        }
		return (new OrderResource($order))
			->response()
			->setStatusCode(Response::HTTP_ACCEPTED);
	}

	public function destroy(Order $order)
	{
		//abort_if(Gate::denies('order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

		$order->delete();

		return response(null, Response::HTTP_NO_CONTENT);
	}

	private function get_payments()
	{

		$order_id_arr = [];

		$order_ids = OrderPayment::get('order_id')->toArray();

		foreach ($order_ids as $id) {
			if (!in_array($id['order_id'], $order_id_arr)) {
				$order_id_arr[] = $id['order_id'];
			}
		}
		return $order_id_arr;
	}
}
