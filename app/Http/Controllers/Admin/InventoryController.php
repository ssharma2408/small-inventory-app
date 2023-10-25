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
use App\Models\ExpensePaymentMaster;
use App\Models\ExpensePayment;
use Gate;
use DB;
use Storage;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class InventoryController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $expense_id_arr = [];
		
		abort_if(Gate::denies('inventory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventories = Inventory::with(['supplier', 'product', 'tax', 'media'])->get();
		
		$expense_id_arr = $this->get_payments();

        return view('admin.inventories.index', compact('inventories', 'expense_id_arr'));
    }

    public function create()
    {
        abort_if(Gate::denies('inventory_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $suppliers = Supplier::pluck('supplier_name', 'id')->prepend(trans('global.pleaseSelect'), '');        
		
		$categories = Category::where('category_id', null)->pluck('name', 'id');
		
		$taxes = Tax::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.inventories.create', compact('suppliers', 'categories', 'taxes'));
    }

    public function store(StoreInventoryRequest $request)
    {
        $expense_master = ExpensePaymentMaster::where(['supplier_id'=>$request->supplier_id , 'invoice_number'=>$request->invoice_number])->first();		
		
		if(empty($expense_master)){
		
			if($request->hasFile('po_file')){
				
				$expense_pay_detail = [];
				
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
				
				if($request->box_or_unit == "0"){
					$request->stock = $request->stock * $request->package_val;
				}
				
				$inventory = Inventory::create($expense_detail);
				
				$expense_pay_detail['supplier_id'] = $expense_detail['supplier_id'];
				$expense_pay_detail['invoice_number'] = $expense_detail['invoice_number'];
				$expense_pay_detail['expense_total'] = $expense_detail['final_price'];
				$expense_pay_detail['expense_paid'] = 0;
				$expense_pay_detail['expense_pending'] = $expense_detail['final_price'];
				$expense_pay_detail['payment_status'] = 0;
				$expense_pay_detail['expense_id'] = $inventory->id;
				
				ExpensePaymentMaster::create($expense_pay_detail);
			
				$product = Product::find($request->product_id);
				$product->increment('stock', $request->stock);

				return redirect()->route('admin.inventories.index');
			}
		}else{
			return redirect()->route('admin.inventories.index')->withErrors("Invoice number already exist for this supplier");
		}
    }

    public function edit(Inventory $inventory)
    {
        abort_if(Gate::denies('inventory_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        
		$expense_id_arr = $this->get_payments();
		
		if(in_array($inventory->id, $expense_id_arr)){
			return redirect()->route('admin.inventories.index')->withErrors("Can't edit this expense");
		}
		
		$suppliers = Supplier::pluck('supplier_name', 'id')->prepend(trans('global.pleaseSelect'), '');        

        $inventory->load('supplier', 'product', 'tax');		
		
		$categories = Category::where('category_id', null)->pluck('name', 'id');
		
		$taxes = Tax::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.inventories.edit', compact('inventory', 'suppliers', 'categories', 'taxes'));
    }

    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
		$product = Product::find($request->product_id);
		
		if(($inventory->stock != $request->stock) || ($inventory->box_or_unit != $request->box_or_unit)){
			
			$inc_stock = 0;
			$dec_stock = 0;
			
		/* 	if($inventory->box_or_unit != $request->box_or_unit){
				
				if($request->box_or_unit == "0"){
					$inc_stock = $request->stock * $request->package_val;
					$dec_stock = $request->stock;
				}else{
					$dec_stock = $request->stock * $request->package_val;
					$inc_stock = $request->stock;
				}
			} */

 			if($inventory->stock != $request->stock){
				if($request->box_or_unit == "0"){
					$dec_stock = $inventory->stock * $request->package_val;
					$inc_stock = $request->stock * $request->package_val;					
				}else{
					$dec_stock = $inventory->stock;
					$inc_stock = $request->stock;
				}
			} 

			$product->decrement('stock', $dec_stock);
			$product->increment('stock', $inc_stock);			
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
		
		if(($inventory->final_price != $request->final_price)){
			ExpensePaymentMaster::where('expense_id', $inventory->id)
				   ->update([
					   'expense_total' => $request->final_price,
					   'expense_pending' => $request->final_price
					]);
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

        $product = Product::find($inventory->product_id);
		
		if($inventory->box_or_unit == "0"){
			$inventory->stock = $inventory->stock * $product->box_size;
		}
		
		$product->decrement('stock', $inventory->stock);
		$inventory->delete();

        return back();
    }

    public function massDestroy(MassDestroyInventoryRequest $request)
    {
        $inventories = Inventory::find(request('ids'));

        foreach ($inventories as $inventory) {
            $product = Product::find($inventory->product_id);
			if($inventory->box_or_unit == "0"){
				$inventory->stock = $inventory->stock * $product->box_size;
			}
			$product->decrement('stock', $inventory->stock);
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
	
	public function get_products($cat_id, $sub_cat_id){
		
		if($cat_id == ""){
			return false;
		}

		$products = Product::select('id', 'name')->where('category_id', $cat_id);
		
		if($sub_cat_id != 0){
			$products = $products->where('sub_category_id', $sub_cat_id);
		}
		
		$products = $products->get();
		
		return response()->json(array('success'=>1, 'products'=>$products), 200);
	}
	
	public function payment($expense_id=""){		
		
			$status_arr = array("Unpaid", "Paid", "Partial Paid");
			
			$payment_arr = [];			
			$payment_query = DB::table('expense_payments')
				->select('expense_payments.amount', 'expense_payments.description', 'expense_payments.date', 'expense_payment_master.invoice_number', 'expense_payment_master.expense_total', 'expense_payment_master.expense_paid', 'expense_payment_master.expense_pending', 'expense_payment_master.payment_status', 'expense_payment_master.expense_id', 'suppliers.supplier_name', 'suppliers.supplier_number', 'suppliers.supplier_email', 'payment_methods.name')
				->leftJoin('expense_payment_master','expense_payment_master.expense_id','=','expense_payments.expense_id')
				->join('suppliers','suppliers.id','=','expense_payment_master.supplier_id')
				->join('payment_methods','payment_methods.id','=','expense_payments.payment_id');
				
			if($expense_id != ""){
				$payment_query->where('expense_payment_master.expense_id','=',$expense_id);
			}
			
			$payment_details = $payment_query->get()->toArray();
				
			foreach($payment_details as $detail){
				
				$payment_arr[$detail->expense_id] = array('invoice_number'=>$detail->invoice_number, 'expense_total'=>$detail->expense_total, 'expense_paid'=>$detail->expense_paid, 'expense_pending'=>$detail->expense_pending, 'payment_status'=>$status_arr[$detail->payment_status], 'supplier_name'=>$detail->supplier_name, 'supplier_number'=>$detail->supplier_number, 'supplier_email'=>$detail->supplier_email, 'payment_detail'=>[]);				
			}

			foreach($payment_details as $detail){
				
				$payment_arr[$detail->expense_id]['payment_detail'][] = array('amount'=>$detail->amount, 'description'=>$detail->description, 'date'=>$detail->date, 'name'=>$detail->name);				
			}	
		
		return view('admin.inventories.payment_history', compact('payment_arr'));
		
	}
	
	private function get_payments(){
		
		$expense_id_arr = [];
		
		$expense_ids = ExpensePayment::get('expense_id')->toArray();		
		
		foreach($expense_ids as $id){
			$expense_id_arr[] = $id['expense_id'];
		}
		$expense_id_arr = array_unique($expense_id_arr);
		
		return $expense_id_arr;
	}
}
