<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\Admin\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use DB;

class OrdersApiController extends Controller
{
    public function index()
    {
        //abort_if(Gate::denies('order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OrderResource(Order::with(['sales_manager', 'customer'])->get());
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

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Order $order)
    {
        //abort_if(Gate::denies('order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order_items = OrderItem::where('order_id', $order->id)->get();

		$item_arr = [];

		foreach($order_items as $item){
			$item_arr[] = $item;
		}		
		$order_details = $order->load(['sales_manager', 'customer']);

		$order_details['order_items'] = $item_arr;		

        return new OrderResource($order_details);
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
}
