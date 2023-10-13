<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category;
use Gate;
use Storage;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::with(['media'])->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $categories = Category::whereNull('category_id')->with('childcategories.childcategories')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {		
		if($request->hasFile('product_image')){
			$file = $request->file('product_image');
			
			$extension  = $file->getClientOriginalExtension();
			$name = time() .'_' . str_replace(" ", "_", $request->name) . '.' . $extension;
			
			$store = Storage::disk('do')->put(
				'/'.$_ENV['DO_FOLDER'].'/'.$name,
				file_get_contents($request->file('product_image')->getRealPath()),
				'public'
				);
			
			/* $url = Storage::disk('do')->url('/'.$_ENV['DO_FOLDER'].'/'.$name);
			
			$cdn_url = str_replace('digitaloceanspaces', 'cdn.digitaloceanspaces', $url); */
			
			$product_detail = $request->all();
			
			$product_detail['image_url'] = $name;

			$product = Product::create($product_detail);			

			return redirect()->route('admin.products.index');
		}
		
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::whereNull('category_id')->with('childcategories.childcategories')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product_detail = $request->all();
		
		if($request->hasFile('product_image')){
			$file = $request->file('product_image');
			
			$extension  = $file->getClientOriginalExtension();
			$name = time() .'_' . str_replace(" ", "_", $request->name) . '.' . $extension;
			
			$store = Storage::disk('do')->put(
				'/'.$_ENV['DO_FOLDER'].'/'.$name,
				file_get_contents($request->file('product_image')->getRealPath()),
				'public'
				);
			
			/* $url = Storage::disk('do')->url('/'.$_ENV['DO_FOLDER'].'/'.$name);
			
			$product_detail['image_url'] = str_replace('digitaloceanspaces', 'cdn.digitaloceanspaces', $url); */
			
			$product_detail['image_url'] = $name;
		}		
		
		$product->update($product_detail);  

        return redirect()->route('admin.products.index');
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        $products = Product::find(request('ids'));

        foreach ($products as $product) {
            $product->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('product_create') && Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Product();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
	
	public function get_package_size($prod_id){
		
		if($prod_id == ""){
			return false;
		}
		$product = Product::select('box_size')->where('id', $prod_id)->first();
		
		return response()->json(array('success'=>1, 'product'=>$product), 200);
	}
}
