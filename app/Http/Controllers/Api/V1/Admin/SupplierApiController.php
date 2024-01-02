<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\Admin\SupplierResource;
use App\Models\Supplier;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SupplierApiController extends Controller
{
    public function index()
    {
        $payment_arr = [];
		//abort_if(Gate::denies('supplier_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
		
		$suppliers = Supplier::all();
		 
		$payments = DB::table('suppliers')
				->select('expense_payment_master.expense_total', 'expense_payment_master.expense_paid', 'expense_payment_master.expense_pending', 'suppliers.id')
				->join('expense_payment_master','expense_payment_master.supplier_id','=','suppliers.id')
				->get()->toArray();

		
		foreach($payments as $pay){
			$pay = (array) $pay;
			if(!array_key_exists($pay['id'], $payment_arr)) {				
				$payment_arr[$pay['id']] = 0;
			}

			$payment_arr[$pay['id']] += $pay['expense_total'];
		}
		
		return response()->json([
            'suppliers' => $suppliers,
            'payment_arr'=> $payment_arr
        ], 200);       
    }

    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create($request->all());

        return (new SupplierResource($supplier))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Supplier $supplier)
    {
        //abort_if(Gate::denies('supplier_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new SupplierResource($supplier);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->all());

        return (new SupplierResource($supplier))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Supplier $supplier)
    {
        //abort_if(Gate::denies('supplier_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $supplier->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
