<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'maximum_selling_price' => [
                'numeric',
                'required',
            ],
            'selling_price' => [
                'numeric',
                'required',
            ],
            'image_url' => [
                'required',
            ],
			'tax_id' => [
                'required',
                'integer',
            ],
            'box_size' => [
                'required',
                'integer',
                'min:1',
                'max:2147483647',
            ],
        ];
    }
}
