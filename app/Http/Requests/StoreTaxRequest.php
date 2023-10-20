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
        //return Gate::allows('tax_create');
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'unique:taxes',
            ],
            'tax' => [
                'numeric',
                'required',
            ],
        ];
    }
}
