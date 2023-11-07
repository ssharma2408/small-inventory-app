<?php

namespace App\Http\Requests;

use App\Models\CreditNote;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCreditNoteRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('credit_note_edit');
    }

    public function rules()
    {
        return [
            'order_id' => [
                'required',
                'integer',
            ],
            'amount' => [
                'numeric',
                'required',
                'min:0',
            ],
            'date' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
        ];
    }
}
