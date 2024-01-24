<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Resources\Admin\CartResource;
use App\Models\Cart;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('cart_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CartResource(Cart::all());
    }

    public function store(StoreCartRequest $request)
    {
        
		DB::table('cart')->where('customer_id', $request['customer_id'])->delete();
		
		$data = [];
		for ($i = 0; $i < count($request['product_id']); $i++) {
			if (!empty($request['product_id']) && !empty($request['customer_id'])) {
				$item = [];
				$item['customer_id'] = $request['customer_id'];
				$item['product_id'] = $request['product_id'][$i];
				$item['price'] = $request['price'][$i];
				$item['quantity'] = $request['quantity'][$i];
				$item['tax_id'] = $request['tax_id'][$i];				
				$item['is_box'] = $request['is_box'][$i];
				$data[] = $item;
			}
		}

		if (!empty($data)) {
			Cart::insert($data);
		}
		
		$cart = Cart::where('customer_id', $request['customer_id'])->get();
		
        return (new CartResource($cart))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Cart $cart)
    {
        abort_if(Gate::denies('cart_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CartResource($cart);
    }

    public function update(UpdateCartRequest $request, Cart $cart)
    {
        $cart->update($request->all());

        return (new CartResource($cart))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Cart $cart)
    {
        abort_if(Gate::denies('cart_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cart->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
