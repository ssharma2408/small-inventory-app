<?php

namespace App\Http\Requests;

use App\Models\ExpensePayment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreExpensePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('expense_payment_create');
    }

    public function rules()
    {
        return [
            'invoice_id' => [
                'required',
				'integer',
            ],
            'payment_id' => [
                'required',
                'integer',
            ],
            'amount' => [
                'numeric',
                'required',
            ],
            'date' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
        ];
    }
}
