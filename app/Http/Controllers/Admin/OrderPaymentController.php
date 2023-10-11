<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOrderPaymentRequest;
use App\Http\Requests\StoreOrderPaymentRequest;
use App\Http\Requests\UpdateOrderPaymentRequest;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\PaymentMethod;
use Gate;
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

        $orders = Order::pluck('order_total', 'id')->prepend(trans('global.pleaseSelect'), '');

        $payments = PaymentMethod::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.orderPayments.create', compact('orders', 'payments'));
    }

    public function store(StoreOrderPaymentRequest $request)
    {
        $orderPayment = OrderPayment::create($request->all());

        return redirect()->route('admin.order-payments.index');
    }

    public function edit(OrderPayment $orderPayment)
    {
        abort_if(Gate::denies('order_payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::pluck('order_total', 'id')->prepend(trans('global.pleaseSelect'), '');

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
}
