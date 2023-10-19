<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySupplierRequest;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use App\Models\Inventory;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SupplierController extends Controller
{
    public function index()
    {
        $payment_arr = [];
		abort_if(Gate::denies('supplier_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

        return view('admin.suppliers.index', compact('suppliers', 'payment_arr'));
    }

    public function create()
    {
        abort_if(Gate::denies('supplier_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create($request->all());

        return redirect()->route('admin.suppliers.index');
    }

    public function edit(Supplier $supplier)
    {
        abort_if(Gate::denies('supplier_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->all());

        return redirect()->route('admin.suppliers.index');
    }

    public function show(Supplier $supplier)
    {
        abort_if(Gate::denies('supplier_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $supplier->load('supplierInventories');

        return view('admin.suppliers.show', compact('supplier'));
    }

    public function destroy(Supplier $supplier)
    {
        abort_if(Gate::denies('supplier_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $supplier->delete();

        return back();
    }

    public function massDestroy(MassDestroySupplierRequest $request)
    {
        $suppliers = Supplier::find(request('ids'));

        foreach ($suppliers as $supplier) {
            $supplier->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function expenses($supplier_id){
		if($supplier_id ==""){
			return;
		}
		
		$payments = DB::table('suppliers')
				->select('expense_payment_master.invoice_number', 'expense_payment_master.expense_total', 'expense_payment_master.expense_paid', 'expense_payment_master.expense_pending', 'expense_payment_master.expense_id', 'suppliers.supplier_name', 'suppliers.supplier_number', 'suppliers.supplier_email')
				->join('expense_payment_master','expense_payment_master.supplier_id','=','suppliers.id')
				->where('expense_payment_master.supplier_id','=',$supplier_id)->get()->toArray();
		
		return view('admin.suppliers.payment_history', compact('payments'));
	}
}
