<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use Gate;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$products = Product::with(['media', 'tax_details'])->get();

        return new ProductResource($products);
    }

    public function getproductbysubcategory($id, $cust_id=0)
    {
        $products = Product::where('sub_category_id',$id)->with(['media', 'tax_details'])->orderBy('product_order', 'ASC')->get();
				
		$product_arr = [];
		
		if($cust_id){
			
			$prods = DB::table('order_items')
						->join('orders', 'order_items.order_id', '=', 'orders.id')
						->select(DB::raw('MAX(order_items.sale_price) as sale_price'), 'order_items.product_id')
						->whereIn('orders.status', [1, 4])
						->where([
								['orders.deleted_at', NULL],
								['order_items.is_box', 1],
								['order_items.sub_category_id', $id],
								['orders.customer_id', $cust_id]
						])
						->groupBy('order_items.product_id')
						->get();
						
			foreach($prods as $prod){
				$product_arr[$prod->product_id] = $prod->sale_price;
			}			
		}		
		
		$i = 0;
		foreach($products as $product){
			if(array_key_exists($product->id, $product_arr)){
				$products[$i]['sales_price'] = $product_arr[$product->id];
			}else{
				$products[$i]['sales_price'] = $product->maximum_selling_price;
			}
			$i++;
		}		
		
        return new ProductResource($products);
    }

    public function store(StoreProductRequest $request)
    {
            /* $url = Storage::disk('do')->url('/'.$_ENV['DO_FOLDER'].'/'.$name);
			
			$cdn_url = str_replace('digitaloceanspaces', 'cdn.digitaloceanspaces', $url); */

            $product_detail = $request->all();
            $product = Product::create($product_detail);

            return (new ProductResource($product))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProductResource($product);
    }

    public function update(Request $request, Product $product)
    {
        $product_detail = $request->all();
		$product->update($product_detail);
        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function gettrendingproducts()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$products = Product::where('show_fe', 1)->with(['media', 'tax_details'])->get();

        return new ProductResource($products);
    }	
}
