<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCustomerRequest;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomersController extends Controller
{
    public function index()
    {
        $payment_arr = [];
		abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = Customer::all();
		
		$payments = DB::table('customers')
				->select('order_payment_master.order_total', 'order_payment_master.order_paid', 'order_payment_master.order_pending', 'customers.id')
				->join('order_payment_master','order_payment_master.customer_id','=','customers.id')
				->get()->toArray();

		
		foreach($payments as $pay){
			$pay = (array) $pay;			
			if(!array_key_exists($pay['id'], $payment_arr)) {				
				$payment_arr[$pay['id']] = 0;
			}
			
			$payment_arr[$pay['id']] += $pay['order_total'];
		}

        return view('admin.customers.index', compact('customers', 'payment_arr'));
    }

    public function create()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->all());

        return redirect()->route('admin.customers.index');
    }

    public function edit(Customer $customer)
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());

        return redirect()->route('admin.customers.index');
    }

    public function show(Customer $customer)
    {
        abort_if(Gate::denies('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.customers.show', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer->delete();

        return back();
    }

    public function massDestroy(MassDestroyCustomerRequest $request)
    {
        $customers = Customer::find(request('ids'));

        foreach ($customers as $customer) {
            $customer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function revenue($customer_id){
		if($customer_id ==""){
			return;
		}
		
		$payments = DB::table('customers')
				->select('order_payment_master.order_number', 'order_payment_master.order_total', 'order_payment_master.order_paid', 'order_payment_master.order_pending', 'customers.name', 'customers.phone_number', 'customers.email')
				->join('order_payment_master','order_payment_master.customer_id','=','customers.id')
				->where('order_payment_master.customer_id','=',$customer_id)->get()->toArray();
		
		return view('admin.customers.payment_history', compact('payments'));
	}
}
