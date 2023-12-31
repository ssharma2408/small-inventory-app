<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize()
    {
        //return Gate::allows('customer_edit');
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'address' => [
                'required',
            ],
            'phone_number' => [
                'string',
                'required',
            ],
			'payment_terms' => [
                'required',
            ],
        ];
    }
}
