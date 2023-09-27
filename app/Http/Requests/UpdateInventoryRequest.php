<?php

namespace App\Http\Requests;

use App\Models\Inventory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateInventoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('inventory_edit');
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
