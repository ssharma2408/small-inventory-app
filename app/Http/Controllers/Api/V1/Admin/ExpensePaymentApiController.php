<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpensePaymentRequest;
use App\Http\Requests\UpdateExpensePaymentRequest;
use App\Http\Resources\Admin\ExpensePaymentResource;
use App\Models\ExpensePayment;
use App\Models\ExpensePaymentMaster;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpensePaymentApiController extends Controller
{

    public function get_invoice()
    {
        $invoices = DB::table('expense_payment_master')
            ->select('expense_payment_master.invoice_number', 'suppliers.supplier_name', 'suppliers.id', 'expense_payment_master.expense_id')
            ->join('suppliers', 'expense_payment_master.supplier_id', '=', 'suppliers.id')
            ->whereNotIn('payment_status', [1])
            ->get();
        return new ExpensePaymentResource($invoices);
    }

    public function index()
    {
        abort_if(Gate::denies('expense_payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ExpensePaymentResource(ExpensePayment::with(['expense', 'payment'])->get());
    }

    public function store(StoreExpensePaymentRequest $request)
    {
        $invoice_pay_details = ExpensePaymentMaster::where('expense_id', $request->invoice_id)->first()->toArray();

        if ($invoice_pay_details['expense_pending'] >= $request->amount) {
            $row = ExpensePaymentMaster::find($invoice_pay_details['id']);
            $row->increment('expense_paid', $request->amount);
            $row->decrement('expense_pending', $request->amount);

            if ((($invoice_pay_details['expense_paid'] + $request->amount) == $invoice_pay_details['expense_total']) && (($invoice_pay_details['expense_pending'] - $request->amount == 0))) {
                $row->payment_status = 1;
            } else {
                $row->payment_status = 2;
            }
            $row->save();
        }

        $expense_detail = $request->all();

        $expense_detail['expense_id'] = $request->invoice_id;

        $expensePayment = ExpensePayment::create($expense_detail);

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

        $expense_master = ExpensePaymentMaster::where('expense_id', $expensePayment->expense_id)->first();

        $status = ($expense_master->expense_pending - $expensePayment->amount == 0) ? 0 : 2;

        ExpensePaymentMaster::where('expense_id', $expensePayment->expense_id)
            ->update([
                'payment_status' => $status
            ]);

        $expense_master->decrement('expense_paid', $expensePayment->amount);
        $expense_master->increment('expense_pending', $expensePayment->amount);

        $expensePayment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
