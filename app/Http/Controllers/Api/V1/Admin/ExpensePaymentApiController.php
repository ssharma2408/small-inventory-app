<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpensePaymentRequest;
use App\Http\Requests\UpdateExpensePaymentRequest;
use App\Http\Resources\Admin\ExpensePaymentResource;
use App\Models\ExpensePayment;
use Gate;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpensePaymentApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('expense_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ExpensePaymentResource(ExpensePayment::with(['expense', 'payment'])->get());
    }

    public function store(StoreExpensePaymentRequest $request)
    {
        $expensePayment = ExpensePayment::create($request->all());

        return (new ExpensePaymentResource($expensePayment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ExpensePayment $expensePayment)
    {
        abort_if(Gate::denies('expense_payment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ExpensePaymentResource($expensePayment->load(['expense', 'payment']));
    }

    public function update(UpdateExpensePaymentRequest $request, ExpensePayment $expensePayment)
    {
        $expensePayment->update($request->all());

        return (new ExpensePaymentResource($expensePayment))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ExpensePayment $expensePayment)
    {
        abort_if(Gate::denies('expense_payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expensePayment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
