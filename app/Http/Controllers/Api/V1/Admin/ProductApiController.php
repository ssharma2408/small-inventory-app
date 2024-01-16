<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use Gate;
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

    public function getproductbysubcategory($id)
    {
        $products = Product::where('sub_category_id',$id)->with(['media', 'tax_details'])->get();
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
}
