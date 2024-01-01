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
			'order_total_without_tax' => [
                'numeric',
                'required',
				'min:0',
            ],
			'order_tax' => [
                'numeric',
                'required',
				'min:0',
            ],
            'order_total' => [
                'numeric',
                'required',
				'min:0',
            ],
            'status' => [
                'required',
            ],
			'discount_type' => [
                'required',
            ],	
			'order_date' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
        ];
    }
}
