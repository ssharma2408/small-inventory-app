<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Http\Resources\Admin\InventoryResource;
use App\Models\Inventory;
use App\Models\ExpensePaymentMaster;
use App\Models\ExpensePayment;
use App\Models\ExpenseItem;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;

class InventoryApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $expense_id_arr = [];
        abort_if(Gate::denies('inventory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $inventories = Inventory::with(['supplier', 'media'])->get();
        $expense_id = $this->get_payments();
        foreach ($inventories as $rw) {
            $rw['edit_key'] = in_array($rw->id, $expense_id) ? 1 : 0;
            array_push($expense_id_arr, $rw);
        }
        return new InventoryResource($expense_id_arr);
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
					$item['sub_category_id'] = isset($request['item_subcategory'][$i]) ? $request['item_subcategory'][$i] : null;
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
            $inventories = Inventory::where('id', $inventory->id)->with(['supplier'])->get();
            return (new InventoryResource($inventories))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        }
    }

    public function show(Inventory $inventory)
    {
        abort_if(Gate::denies('inventory_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$inventory = $inventory->load('supplier');
		
		$inventory['order_item'] = DB::table('expense_items')
                ->join('products', 'expense_items.product_id', '=', 'products.id')
                ->join('categories', 'expense_items.category_id', '=', 'categories.id')
                ->join('categories as c', 'expense_items.sub_category_id', '=', 'c.id')
                ->join('taxes', 'taxes.id', '=', 'expense_items.tax_id')
                ->select('c.name as sub_category_name', 'c.id as sub_category_id', 'categories.name as category_name', 'categories.id as category_id', 'products.name', 'expense_items.product_id', 'expense_items.stock', 'expense_items.exp_date', 'expense_items.is_box', 'expense_items.purchase_price', 'expense_items.tax_id', 'products.box_size', 'taxes.tax', 'taxes.title')
                ->where('expense_items.expense_id', $inventory->id)
                ->get();

        return new InventoryResource($inventory);
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

        return (new InventoryResource($inventory))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Inventory $inventory)
    {
        //abort_if(Gate::denies('inventory_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $inventory->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function get_payments()
    {

        $expense_id_arr = [];

        $expense_ids = ExpensePayment::get('expense_id')->toArray();

        foreach ($expense_ids as $id) {
            $expense_id_arr[] = $id['expense_id'];
        }
        $expense_id_arr = array_unique($expense_id_arr);

        return $expense_id_arr;
    }
}
