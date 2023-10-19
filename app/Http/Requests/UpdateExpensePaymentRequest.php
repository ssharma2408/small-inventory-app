<?php

namespace App\Http\Requests;

use App\Models\ExpensePayment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateExpensePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('expense_payment_edit');
    }

    public function rules()
    {
        return [            
            'payment_id' => [
                'required',
                'integer',
            ],            
            'date' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
        ];
    }
}
