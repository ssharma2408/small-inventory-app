<?php

namespace App\Http\Requests;

use App\Models\Cart;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCartRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('cart_edit');
    }

    public function rules()
    {
        return [
            'customer_id' => [
                'required',
                'integer',
            ],
			'product_id' => [
                'required',                
            ],
			'price' => [
                'required',                
            ],
			'quantity' => [
                'required',                
            ],
			'tax_id ' => [
                'required',                
            ],
			'is_box ' => [
                'required',                
            ],
        ];
    }
}
