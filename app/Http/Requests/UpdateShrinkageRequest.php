<?php

namespace App\Http\Requests;

use App\Models\Shrinkage;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateShrinkageRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('shrinkage_edit');
    }

    public function rules()
    {
        return [
            'product_id' => [
                'required',
                'integer',
            ],
            'number' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'description' => [
                'required',
            ],
            'added_by_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
