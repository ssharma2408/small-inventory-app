<?php

namespace App\Http\Requests;

use App\Models\Tax;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTaxRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tax_create');
    }

    public function rules()
    {
        return [
            'tax' => [
                'numeric',
                'required',
                'unique:taxes,tax',
            ],
        ];
    }
}
