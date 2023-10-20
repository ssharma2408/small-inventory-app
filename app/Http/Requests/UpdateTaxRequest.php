<?php

namespace App\Http\Requests;

use App\Models\Tax;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTaxRequest extends FormRequest
{
    public function authorize()
    {
        //return Gate::allows('tax_edit');
        return true;
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
                'unique:taxes,title,' . request()->route('tax')->id,
            ],
            'tax' => [
                'numeric',
                'required',
            ],
        ];
    }
}
