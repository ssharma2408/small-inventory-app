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
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $expense_id_arr = [];
        abort_if(Gate::denies('inventory_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $inventories = Inventory::with(['supplier', 'product', 'tax', 'media'])->get();
        $expense_id = $this->get_payments();
        foreach ($inventories as $rw) {
            $rw['edit_key'] = in_array($rw->id,$expense_id) ? 1 : 0;
            array_push($expense_id_arr, $rw);
        }

        return $expense_id_arr;
        return new InventoryResource($inventories);
    }

    public function store(StoreInventoryRequest $request)
    {
        $expense_master = ExpensePaymentMaster::where(['supplier_id' => $request->supplier_id, 'invoice_number' => $request->invoice_number])->first();

        if (empty($expense_master)) {

            if ($request->hasFile('po_file')) {

                $expense_pay_detail = [];

                $file = $request->file('po_file');

                $extension  = $file->getClientOriginalExtension();
                $name = time() . '.' . $extension;

                $store = Storage::disk('do')->put(
                    '/' . $_ENV['DO_FOLDER'] . '/' . $name,
                    file_get_contents($request->file('po_file')->getRealPath()),
                    'public'
                );

                $expense_detail = $request->all();

                $expense_detail['image_url'] = $name;

                if ($request->box_or_unit == "0") {
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
                $inventories = Inventory::where('id', $inventory->id)->with(['supplier', 'product', 'tax', 'media'])->get();
                return (new InventoryResource($inventories))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
            }
        } else {
        }
    }

    public function show(Inventory $inventory)
    {
        abort_if(Gate::denies('inventory_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new InventoryResource($inventory->load(['supplier', 'product', 'tax']));
    }

    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
        $product = Product::find($request->product_id);

        if (($inventory->stock != $request->stock) || ($inventory->box_or_unit != $request->box_or_unit)) {

            if ($inventory->box_or_unit != $request->box_or_unit) {

                if ($request->box_or_unit == "0") {
                    $request->stock = $request->stock * $request->package_val;
                } else {
                    $inventory->stock = $request->stock * $request->package_val;
                }
            }

            $product->decrement('stock', $inventory->stock);
            $product->increment('stock', $request->stock);
        }

        $expense_detail = $request->all();

        if ($request->hasFile('po_file')) {

            $file = $request->file('po_file');

            $extension  = $file->getClientOriginalExtension();
            $name = time() . '.' . $extension;

            $store = Storage::disk('do')->put(
                '/' . $_ENV['DO_FOLDER'] . '/' . $name,
                file_get_contents($request->file('po_file')->getRealPath()),
                'public'
            );
            $expense_detail['image_url'] = $name;
        }

        if (($inventory->final_price != $request->final_price)) {
            ExpensePaymentMaster::where('expense_id', $inventory->id)
                ->update([
                    'expense_total' => $request->final_price,
                    'expense_pending' => $request->final_price
                ]);
        }

        $inventory->update($expense_detail);

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
