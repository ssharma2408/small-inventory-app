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
use App\Models\Tax;
use Gate;
use Storage;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('inventory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventories = Inventory::with(['supplier', 'product', 'tax', 'media'])->get();

        return view('admin.inventories.index', compact('inventories'));
    }

    public function create()
    {
        abort_if(Gate::denies('inventory_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $suppliers = Supplier::pluck('supplier_name', 'id')->prepend(trans('global.pleaseSelect'), '');        
		
		$categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $categories = Category::whereNull('category_id')->with('childcategories.childcategories')->get();
		
		$taxes = Tax::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.inventories.create', compact('suppliers', 'categories', 'taxes'));
    }

    public function store(StoreInventoryRequest $request)
    {
        if($request->hasFile('po_file')){
			$file = $request->file('po_file');
			
			$extension  = $file->getClientOriginalExtension();
			$name = time() . '.' . $extension;
			
			$store = Storage::disk('do')->put(
				'/'.$_ENV['DO_FOLDER'].'/'.$name,
				file_get_contents($request->file('po_file')->getRealPath()),
				'public'
				);
				
			$expense_detail = $request->all();
			
			$expense_detail['image_url'] = $name;
			
			$inventory = Inventory::create($expense_detail);		
		
			$product = Product::find($request->product_id);
			$product->increment('stock', $request->stock);       

			return redirect()->route('admin.inventories.index');
		}		
    }

    public function edit(Inventory $inventory)
    {
        abort_if(Gate::denies('inventory_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $suppliers = Supplier::pluck('supplier_name', 'id')->prepend(trans('global.pleaseSelect'), '');        

        $inventory->load('supplier', 'product', 'tax');		
		
		$categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::whereNull('category_id')->with('childcategories.childcategories')->get();
		
		$taxes = Tax::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.inventories.edit', compact('inventory', 'suppliers', 'categories', 'taxes'));
    }

    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
       	
		$product = Product::find($request->product_id);
		
		if($inventory->stock != $request->stock){
			$product->decrement('stock', $inventory->stock);
			$product->increment('stock', $request->stock);			
		}
		
		 $expense_detail = $request->all();
		
		if($request->hasFile('po_file')){
			
			$file = $request->file('po_file');
			
			$extension  = $file->getClientOriginalExtension();
			$name = time() . '.' . $extension;
			
			$store = Storage::disk('do')->put(
				'/'.$_ENV['DO_FOLDER'].'/'.$name,
				file_get_contents($request->file('po_file')->getRealPath()),
				'public'
				);
			$expense_detail['image_url'] = $name;
		}
			
		$inventory->update($expense_detail);		

        return redirect()->route('admin.inventories.index');
    }

    public function show(Inventory $inventory)
    {
        abort_if(Gate::denies('inventory_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventory->load('supplier', 'product', 'tax');

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
