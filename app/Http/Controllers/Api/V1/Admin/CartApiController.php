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
		
		$cart = DB::table('cart')
			->join('products', 'cart.product_id', '=', 'products.id')
			->join('categories', 'cart.category_id', '=', 'categories.id')
			->join('categories as c', 'cart.sub_category_id', '=', 'c.id')
			->join('customers', 'cart.customer_id', '=', 'customers.id')			
			->join('taxes', 'taxes.id', '=', 'cart.tax_id')
			->select('c.name as sub_category_name', 'c.id as sub_category_id', 'categories.name as category_name', 'categories.id as category_id', 'cart.customer_id', 'cart.product_id', 'cart.quantity', 'products.name as product_name', 'cart.is_box', 'cart.price', 'cart.tax_id', 'taxes.title', 'taxes.tax', 'customers.name as customer_name', 'products.box_size', 'products.image_url', 'cart.sales_manager_id', 'products.stock')
			->where('cart.customer_id', $cust_id)
			->where('cart.deleted_at', null)
			->get()->toArray();
		
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
					$item['sales_manager_id'] = $request['sales_manager_id'];
					$item['category_id'] = $request['category_id'][$i];
					$item['sub_category_id'] = $request['sub_category_id'][$i];
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

    public function destroy($cust_id)
    {
        abort_if(Gate::denies('cart_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		DB::table('cart')->where('customer_id', $cust_id)->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function delete_cart_item($cust_id, $prod_id){
		
		DB::table('cart')->where([['customer_id', '=', $cust_id], ['product_id', '=', $prod_id]])->delete();
		
		return response(null, Response::HTTP_NO_CONTENT);
	}
}
