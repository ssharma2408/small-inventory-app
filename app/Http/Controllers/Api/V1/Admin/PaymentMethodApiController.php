<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Http\Resources\Admin\TaxResource;
use App\Models\PaymentMethod;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentMethodApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('payment_method_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return new TaxResource(PaymentMethod::all());
    }

    public function store(StorePaymentMethodRequest $request)
    {
        $paymentMethod = PaymentMethod::create($request->all());

        return (new TaxResource($paymentMethod))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);  
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update($request->all());

        return (new TaxResource($paymentMethod))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function show(PaymentMethod $paymentMethod)
    {
        abort_if(Gate::denies('payment_method_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return new TaxResource($paymentMethod);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        abort_if(Gate::denies('payment_method_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentMethod->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
