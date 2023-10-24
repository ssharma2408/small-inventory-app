<?php

namespace App\Http\Requests;

use App\Models\Order;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOrderRequest extends FormRequest
{
    public function authorize()
    {
        //return Gate::allows('order_edit');
        return true;
    }

    public function rules()
    {
        return [            
			'item_name' => [
                'required',
            ],
            'order_total' => [
                'numeric',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
