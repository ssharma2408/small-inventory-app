<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderPaymentRequest;
use App\Http\Requests\UpdateOrderPaymentRequest;
use App\Http\Resources\Admin\OrderPaymentResource;
use App\Models\OrderPayment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderPaymentApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('order_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OrderPaymentResource(OrderPayment::with(['order', 'payment'])->get());
    }

    public function store(StoreOrderPaymentRequest $request)
    {
        $orderPayment = OrderPayment::create($request->all());

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
