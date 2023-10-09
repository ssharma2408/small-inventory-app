<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyInventoryRequest;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('inventory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventories = Inventory::with(['supplier', 'product', 'media'])->get();

        return view('admin.inventories.index', compact('inventories'));
    }

    public function create()
    {
        abort_if(Gate::denies('inventory_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $suppliers = Supplier::pluck('supplier_name', 'id')->prepend(trans('global.pleaseSelect'), '');        
		
		$categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $categories = Category::whereNull('category_id')->with('childcategories.childcategories')->get();

        return view('admin.inventories.create', compact('suppliers', 'categories'));
    }

    public function store(StoreInventoryRequest $request)
    {
        $inventory = Inventory::create($request->all());		
		
		$product = Product::find($request->product_id);
		$product->increment('stock', $request->stock);

        if ($request->input('po_file', false)) {
            $inventory->addMedia(storage_path('tmp/uploads/' . basename($request->input('po_file'))))->toMediaCollection('po_file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $inventory->id]);
        }

        return redirect()->route('admin.inventories.index');
    }

    public function edit(Inventory $inventory)
    {
        abort_if(Gate::denies('inventory_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $suppliers = Supplier::pluck('supplier_name', 'id')->prepend(trans('global.pleaseSelect'), '');        

        $inventory->load('supplier', 'product');		
		
		$categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::whereNull('category_id')->with('childcategories.childcategories')->get();		

        return view('admin.inventories.edit', compact('inventory', 'suppliers', 'categories'));
    }

    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
       	$product = Product::find($request->product_id);
		
		if($inventory->stock != $request->stock){
			$product->decrement('stock', $inventory->stock);
			$product->increment('stock', $request->stock);			
		}
		
		$inventory->update($request->all());		

        if ($request->input('po_file', false)) {
            if (! $inventory->po_file || $request->input('po_file') !== $inventory->po_file->file_name) {
                if ($inventory->po_file) {
                    $inventory->po_file->delete();
                }
                $inventory->addMedia(storage_path('tmp/uploads/' . basename($request->input('po_file'))))->toMediaCollection('po_file');
            }
        } elseif ($inventory->po_file) {
            $inventory->po_file->delete();
        }

        return redirect()->route('admin.inventories.index');
    }

    public function show(Inventory $inventory)
    {
        abort_if(Gate::denies('inventory_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventory->load('supplier', 'product');

        return view('admin.inventories.show', compact('inventory'));
    }

    public function destroy(Inventory $inventory)
    {
        abort_if(Gate::denies('inventory_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventory->delete();

        return back();
    }

    public function massDestroy(MassDestroyInventoryRequest $request)
    {
        $inventories = Inventory::find(request('ids'));

        foreach ($inventories as $inventory) {
            $inventory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('inventory_create') && Gate::denies('inventory_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Inventory();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
	
	public function get_products($cat_id){
		
		if($cat_id == ""){
			return false;
		}
		$products = Product::select('id', 'name')->where('category_id', $cat_id)->get();
		
		return response()->json(array('success'=>1, 'products'=>$products), 200);
	}
}
