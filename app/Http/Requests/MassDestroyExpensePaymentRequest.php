<?php

namespace App\Http\Requests;

use App\Models\ExpensePayment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyExpensePaymentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('expense_payment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:expense_payments,id',
        ];
    }
}
