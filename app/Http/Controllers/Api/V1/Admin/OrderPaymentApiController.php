<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderPaymentRequest;
use App\Http\Requests\UpdateOrderPaymentRequest;
use App\Http\Resources\Admin\OrderPaymentResource;
use App\Models\OrderPayment;
use App\Models\OrderPaymentMaster;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderPaymentApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('order_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OrderPaymentResource(OrderPayment::with(['order', 'payment'])->get());
    }

    public function getpendingamt()
    {
        $orders = DB::table('order_payment_master')
            ->select('order_payment_master.order_number', 'customers.name', 'order_payment_master.order_pending')
            ->join('customers', 'order_payment_master.customer_id', '=', 'customers.id')
            ->whereNotIn('payment_status', [1])
            ->get();
        return ;
        return (new OrderPaymentResource($orders))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function store(StoreOrderPaymentRequest $request)
    {
        $order_pay_details = OrderPaymentMaster::where('order_number', $request->order_id)->first()->toArray();

        if ($order_pay_details['order_pending'] >= $request->amount) {
            $row = OrderPaymentMaster::find($order_pay_details['id']);
            $row->increment('order_paid', $request->amount);
            $row->decrement('order_pending', $request->amount);

            if ((($order_pay_details['order_paid'] + $request->amount) == $order_pay_details['order_total']) && (($order_pay_details['order_pending'] - $request->amount == 0))) {
                $row->payment_status = 1;
            } else {
                $row->payment_status = 2;
            }
            $row->save();
        }

        $order_detail = $request->all();

        $orderPayment = OrderPayment::create($order_detail);

        return (new OrderPaymentResource($orderPayment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OrderPayment $orderPayment)
    {
        abort_if(Gate::denies('order_payment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OrderPaymentResource($orderPayment->load(['order', 'payment']));
    }

    public function update(UpdateOrderPaymentRequest $request, OrderPayment $orderPayment)
    {
        $orderPayment->update($request->all());

        return (new OrderPaymentResource($orderPayment))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OrderPayment $orderPayment)
    {
        abort_if(Gate::denies('order_payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orderPayment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
