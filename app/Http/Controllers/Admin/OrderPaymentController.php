<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderPaymentRequest;
use App\Http\Requests\StoreOrderPaymentRequest;
use App\Http\Requests\UpdateOrderPaymentRequest;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderPaymentMaster;
use App\Models\PaymentMethod;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderPaymentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('order_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orderPayments = OrderPayment::with(['order', 'payment'])->get();

        return view('admin.orderPayments.index', compact('orderPayments'));
    }

    public function create()
    {
        abort_if(Gate::denies('order_payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');        

		$orders = DB::table('order_payment_master')
					->select('order_payment_master.order_number', 'customers.name')
					->join('customers', 'order_payment_master.customer_id', '=', 'customers.id')
					->whereNotIn('payment_status', [1])
					->get();

        $payments = PaymentMethod::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.orderPayments.create', compact('orders', 'payments'));
    }

    public function store(StoreOrderPaymentRequest $request)
    {
		
		$order_pay_details = OrderPaymentMaster::where('order_number', $request->order_id)->first()->toArray();
		
		if($order_pay_details['order_pending'] >= $request->amount ){
			$row = OrderPaymentMaster::find($order_pay_details['id']);
			$row->increment('order_paid', $request->amount);
			$row->decrement('order_pending', $request->amount);
			
			if((($order_pay_details['order_paid'] + $request->amount) == $order_pay_details['order_total']) && (($order_pay_details['order_pending'] - $request->amount == 0))){
				$row->payment_status = 1;				
			}else{
				$row->payment_status = 2;
			}
			$row->save();
		}
		
		$order_detail = $request->all();		

		$orderPayment = OrderPayment::create($order_detail);

        return redirect()->route('admin.order-payments.index');
    }

    public function edit(OrderPayment $orderPayment)
    {
        abort_if(Gate::denies('order_payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = DB::table('order_payment_master')
					->select('order_payment_master.order_number', 'customers.name')
					->join('customers', 'order_payment_master.customer_id', '=', 'customers.id')
					->whereNotIn('payment_status', [1])
					->get();

        $payments = PaymentMethod::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $orderPayment->load('order', 'payment');

        return view('admin.orderPayments.edit', compact('orderPayment', 'orders', 'payments'));
    }

    public function update(UpdateOrderPaymentRequest $request, OrderPayment $orderPayment)
    {
        $orderPayment->update($request->all());

        return redirect()->route('admin.order-payments.index');
    }

    public function show(OrderPayment $orderPayment)
    {
        abort_if(Gate::denies('order_payment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orderPayment->load('order', 'payment');

        return view('admin.orderPayments.show', compact('orderPayment'));
    }

    public function destroy(OrderPayment $orderPayment)
    {
        abort_if(Gate::denies('order_payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orderPayment->delete();

        return back();
    }

    public function massDestroy(MassDestroyOrderPaymentRequest $request)
    {
        $orderPayments = OrderPayment::find(request('ids'));

        foreach ($orderPayments as $orderPayment) {
            $orderPayment->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
	
	public function get_due_payment($order_id){
		if($order_id == ""){
			return;
		}
		$due_amount = OrderPaymentMaster::select('order_pending')->where('order_number', $order_id)->first();
		
		return response()->json(array('success'=>1, 'due_amount'=>$due_amount), 200);
	}
}
