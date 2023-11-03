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
use App\Models\ExpenseItem;
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

        $inventories = Inventory::with(['supplier', 'media'])->get();
		
		$expense_id_arr = $this->get_payments();

        return view('admin.inventories.index', compact('inventories', 'expense_id_arr'));
    }

    public function create()
    {
        abort_if(Gate::denies('inventory_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $suppliers = Supplier::pluck('supplier_name', 'id')->prepend(trans('global.pleaseSelect'), '');        
		
		$categories = Category::where('category_id', null)->pluck('name', 'id');
		
		$taxes = Tax::select('title', 'id')->get();

        return view('admin.inventories.create', compact('suppliers', 'categories', 'taxes'));
    }

    public function store(StoreInventoryRequest $request)
    {
        $expense_master = ExpensePaymentMaster::where(['supplier_id'=>$request->supplier_id , 'invoice_number'=>$request->invoice_number])->first();		
		
		if(empty($expense_master)){

			$expense_detail = $request->all();
			$expense_pay_detail = [];
			$due_date_arr = Inventory::DAYS_PAYABLE_OUTSTANDING_SELECT;	
			$expense_detail['image_url'] = "";

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
			
			$expense_detail['due_date'] = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s"). ' + '.explode(" ", $due_date_arr[$request->days_payable_outstanding])[0].' days'));

			$inventory = Inventory::create($expense_detail);
			
			$expense_pay_detail['supplier_id'] = $expense_detail['supplier_id'];
			$expense_pay_detail['invoice_number'] = $expense_detail['invoice_number'];
			$expense_pay_detail['expense_total'] = $expense_detail['final_price'];
			$expense_pay_detail['expense_paid'] = 0;
			$expense_pay_detail['expense_pending'] = $expense_detail['final_price'];
			$expense_pay_detail['payment_status'] = 0;
			$expense_pay_detail['expense_id'] = $inventory->id;
			
			ExpensePaymentMaster::create($expense_pay_detail);

			$data = [];
			for ($i = 0; $i < count($request['item_name']); $i++) {
				if (!empty($request['item_name']) && !empty($request['item_stock'])) {						
					
					$stock = $request['item_stock'][$i];
					
					if($request['box_or_unit'][$i] == "1"){
						$stock = $request['item_stock'][$i] * $request['package_val'][$i];
					}
					
					$item = [];
					$item['product_id'] = $request['item_name'][$i];
					$item['expense_id'] = $inventory->id;
					$item['stock'] = $request['item_stock'][$i];
					$item['category_id'] = $request['item_category'][$i];
					$item['sub_category_id'] = $request['item_subcategory'][$i];
					$item['purchase_price'] = $request['item_price'][$i];
					$item['tax_id'] = $request['item_tax_id'][$i];
					$item['is_box'] = $request['box_or_unit'][$i];
					$item['exp_date'] = $request['item_exp_date'][$i];
					$data[] = $item;
					
					$product = Product::find($request['item_name'][$i]);
					$product->increment('stock', $stock);
				}

			}

			if (!empty($data)) {
				ExpenseItem::insert($data);
			}

			return redirect()->route('admin.inventories.index');
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

        $inventory->load('supplier');		
		
		$categories = Category::where('category_id', null)->pluck('name', 'id');
		
		$expense_items = DB::table('expense_items')
                ->join('products', 'expense_items.product_id', '=', 'products.id')
                ->join('categories', 'expense_items.category_id', '=', 'categories.id')
                ->join('categories as c', 'expense_items.sub_category_id', '=', 'c.id')
                ->join('taxes', 'taxes.id', '=', 'expense_items.tax_id')
                ->select('c.name as sub_category_name', 'c.id as sub_category_id', 'categories.name as category_name', 'categories.id as category_id', 'products.name', 'expense_items.product_id', 'expense_items.stock', 'expense_items.is_box', 'expense_items.purchase_price', 'expense_items.tax_id', 'products.box_size', 'taxes.tax', 'expense_items.exp_date')
                ->where('expense_items.expense_id', $inventory->id)
                ->get();
		
		$taxes = Tax::select('title', 'id')->get();

        return view('admin.inventories.edit', compact('inventory', 'suppliers', 'categories', 'taxes', 'expense_items'));
    }

    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
		$due_date_arr = Inventory::DAYS_PAYABLE_OUTSTANDING_SELECT;
				
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
		
		$expense_detail['due_date'] = date('Y-m-d H:i:s', strtotime($inventory->created_at. ' + '.explode(" ", $due_date_arr[$request->days_payable_outstanding])[0].' days'));

		$inventory->update($expense_detail);
		
		$expense_items = DB::table('expense_items')
                ->join('products', 'expense_items.product_id', '=', 'products.id')
				->select('expense_items.product_id', 'expense_items.stock', 'expense_items.is_box', 'expense_items.purchase_price', 'expense_items.tax_id', 'products.box_size')
                ->where('expense_items.expense_id', $inventory->id)
                ->get();				
		
		foreach($expense_items as $ei){
			$old_stock = $ei->stock;
			
			if($ei->is_box){
				$old_stock = $old_stock * $ei->box_size;
			}
			$product = Product::find($ei->product_id);
			$product->decrement('stock', $old_stock);
		}

		DB::table('expense_items')->where('expense_id', $inventory->id)->delete();
		
		$data = [];
		for ($i = 0; $i < count($request['item_name']); $i++) {
			if (!empty($request['item_name']) && !empty($request['item_stock'])) {
				
				$stock = $request['item_stock'][$i];
				
				if($request['box_or_unit'][$i] == "1"){
					$stock = $request['item_stock'][$i] * $request['package_val'][$i];
				}
				
				$item = [];
				$item['product_id'] = $request['item_name'][$i];
				$item['expense_id'] = $inventory->id;
				$item['stock'] = $request['item_stock'][$i];
				$item['category_id'] = $request['item_category'][$i];
				$item['sub_category_id'] = $request['item_subcategory'][$i];
				$item['purchase_price'] = $request['item_price'][$i];
				$item['tax_id'] = $request['item_tax_id'][$i];
				$item['is_box'] = $request['box_or_unit'][$i];
				$item['exp_date'] = $request['item_exp_date'][$i];
				$data[] = $item;
				
				// Stock Mgmt				
				$product = Product::find($request['item_name'][$i]);
				$product->increment('stock', $stock);
			}

		}

		if (!empty($data)) {
			ExpenseItem::insert($data);
		}

        return redirect()->route('admin.inventories.index');
    }

    public function show(Inventory $inventory)
    {
        abort_if(Gate::denies('inventory_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventory->load('supplier');
		
		$expense_items = DB::table('expense_items')
                ->join('products', 'expense_items.product_id', '=', 'products.id')
                ->join('categories', 'expense_items.category_id', '=', 'categories.id')
                ->join('categories as c', 'expense_items.sub_category_id', '=', 'c.id')
                ->join('taxes', 'taxes.id', '=', 'expense_items.tax_id')
                ->select('c.name as sub_category_name', 'c.id as sub_category_id', 'categories.name as category_name', 'categories.id as category_id', 'products.name', 'expense_items.product_id', 'expense_items.stock', 'expense_items.is_box', 'expense_items.purchase_price', 'expense_items.tax_id', 'products.box_size', 'taxes.tax', 'taxes.title')
                ->where('expense_items.expense_id', $inventory->id)
                ->get();

        return view('admin.inventories.show', compact('inventory', 'expense_items'));
    }

    public function destroy(Inventory $inventory)
    {
        abort_if(Gate::denies('inventory_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expense_items = DB::table('expense_items')
                ->join('products', 'expense_items.product_id', '=', 'products.id')
				->select('expense_items.product_id', 'expense_items.stock', 'expense_items.is_box', 'expense_items.purchase_price', 'expense_items.tax_id', 'products.box_size')
                ->where('expense_items.expense_id', $inventory->id)
                ->get();				
		
		foreach($expense_items as $ei){
			$old_stock = $ei->stock;
			
			if($ei->is_box){
				$old_stock = $old_stock * $ei->box_size;
			}
			$product = Product::find($ei->product_id);
			$product->decrement('stock', $old_stock);
		}
		
		$inventory->delete();

        return back();
    }

    public function massDestroy(MassDestroyInventoryRequest $request)
    {
        $inventories = Inventory::find(request('ids'));

        foreach ($inventories as $inventory) {
            $expense_items = DB::table('expense_items')
                ->join('products', 'expense_items.product_id', '=', 'products.id')
				->select('expense_items.product_id', 'expense_items.stock', 'expense_items.is_box', 'expense_items.purchase_price', 'expense_items.tax_id', 'products.box_size')
                ->where('expense_items.expense_id', $inventory->id)
                ->get();				
		
			foreach($expense_items as $ei){
				$old_stock = $ei->stock;
				
				if($ei->is_box){
					$old_stock = $old_stock * $ei->box_size;
				}
				$product = Product::find($ei->product_id);
				$product->decrement('stock', $old_stock);
			}	
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
	
	 public function get_product_detail($id)
    {
        $product = Product::select('id', 'name', 'box_size')->where('id', $id)->first();

        return response()->json(array('success' => 1, 'product' => $product), 200);
    }
}
