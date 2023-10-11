<?php

namespace App\Http\Requests;

use App\Models\PaymentMethod;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPaymentMethodRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('payment_method_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:payment_methods,id',
        ];
    }
}
