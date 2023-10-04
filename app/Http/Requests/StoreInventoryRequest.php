<?php

namespace App\Http\Requests;

use App\Models\Inventory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreInventoryRequest extends FormRequest
{
    public function authorize()
    {
        //return Gate::allows('inventory_create');
        return true;
    }

    public function rules()
    {
        return [
            'product_name' => [
                'string',
                'required',
            ],
            'stock' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'price' => [
                'numeric',
                'required',
            ],
            'discount_type' => [
                'required',
            ],
            'discount' => [
                'numeric',
            ],
            'tax' => [
                'numeric',
                'required',
            ],
            'final_price' => [
                'numeric',
                'required',
            ],
        ];
    }
}
