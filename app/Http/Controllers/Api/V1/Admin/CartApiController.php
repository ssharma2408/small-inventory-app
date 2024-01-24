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
    
	public function index($cust_id)
    {
        abort_if(Gate::denies('cart_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$cart = Cart::where('customer_id', $cust_id)->get();        
		$data = array('customer_id' => $cust_id, 'cart_details' => $cart);
		return response()->json([            
            'data'=> $data
        ], 200);
    }

	public function store(StoreCartRequest $request)
    {
        
		$current_cart = Cart::select('product_id')->where('customer_id', $request['customer_id'])->get();
		
		$current_array = [];
		
		foreach($current_cart as $current){
			$current_array[] = $current->product_id;
			/* if(! in_array($current->product_id, $request['product_id'])){
				Cart::where([['customer_id', '=', $request['customer_id']], ['product_id', '=', $current->product_id]])->delete();
			} */
		}		
		
		for ($i = 0; $i < count($request['product_id']); $i++) {
			if (!empty($request['product_id']) && !empty($request['customer_id'])) {				
				if(in_array($request['product_id'][$i], $current_array)){
					Cart::where([['customer_id', '=', $request['customer_id']], ['product_id', '=', $request['product_id'][$i]]])
					   ->update([
						   'quantity' => $request['quantity'][$i]
						]);
				}else{
					$item = [];
					$item['customer_id'] = $request['customer_id'];
					$item['product_id'] = $request['product_id'][$i];
					$item['price'] = $request['price'][$i];
					$item['quantity'] = $request['quantity'][$i];
					$item['tax_id'] = $request['tax_id'][$i];
					$item['is_box'] = $request['is_box'][$i];
					Cart::insert($item);
				}
			}
		}
		
		$cart = Cart::where('customer_id', $request['customer_id'])->get();
		
        return (new CartResource($cart))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(Cart $cart)
    {
        abort_if(Gate::denies('cart_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cart->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
