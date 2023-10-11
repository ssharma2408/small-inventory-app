<?php

namespace App\Http\Requests;

use App\Models\Tax;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTaxRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('tax_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:taxes,id',
        ];
    }
}
