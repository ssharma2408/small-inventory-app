<?php

namespace App\Http\Requests;

use App\Models\Order;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        //return Gate::allows('order_create');
        return true;
    }

    public function rules()
    {
        return [
            'sales_manager_id' => [
                'required',
                'integer',
            ],
            'customer_id' => [
                'required',
                'integer',
            ],
			'item_name' => [
                'required',
            ],
			'order_total_without_tax' => [
                'numeric',
                'required',
            ],
			'order_tax' => [
                'numeric',
                'required',
            ],
            'order_total' => [
                'numeric',
                'required',
            ],
			'discount_type' => [
                'required',
            ],
            'status' => [
                'required',
            ],
			'order_date' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
        ];
    }
}
