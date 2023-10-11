<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyExpensePaymentRequest;
use App\Http\Requests\StoreExpensePaymentRequest;
use App\Http\Requests\UpdateExpensePaymentRequest;
use App\Models\ExpensePayment;
use App\Models\Inventory;
use App\Models\PaymentMethod;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpensePaymentController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('expense_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expensePayments = ExpensePayment::with(['expense', 'payment'])->get();

        return view('admin.expensePayments.index', compact('expensePayments'));
    }

    public function create()
    {
        abort_if(Gate::denies('expense_payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expenses = Inventory::pluck('stock', 'id')->prepend(trans('global.pleaseSelect'), '');

        $payments = PaymentMethod::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.expensePayments.create', compact('expenses', 'payments'));
    }

    public function store(StoreExpensePaymentRequest $request)
    {
        $expensePayment = ExpensePayment::create($request->all());

        return redirect()->route('admin.expense-payments.index');
    }

    public function edit(ExpensePayment $expensePayment)
    {
        abort_if(Gate::denies('expense_payment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expenses = Inventory::pluck('stock', 'id')->prepend(trans('global.pleaseSelect'), '');

        $payments = PaymentMethod::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $expensePayment->load('expense', 'payment');

        return view('admin.expensePayments.edit', compact('expensePayment', 'expenses', 'payments'));
    }

    public function update(UpdateExpensePaymentRequest $request, ExpensePayment $expensePayment)
    {
        $expensePayment->update($request->all());

        return redirect()->route('admin.expense-payments.index');
    }

    public function show(ExpensePayment $expensePayment)
    {
        abort_if(Gate::denies('expense_payment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expensePayment->load('expense', 'payment');

        return view('admin.expensePayments.show', compact('expensePayment'));
    }

    public function destroy(ExpensePayment $expensePayment)
    {
        abort_if(Gate::denies('expense_payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $expensePayment->delete();

        return back();
    }

    public function massDestroy(MassDestroyExpensePaymentRequest $request)
    {
        $expensePayments = ExpensePayment::find(request('ids'));

        foreach ($expensePayments as $expensePayment) {
            $expensePayment->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
