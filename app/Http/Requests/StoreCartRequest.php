<?php

namespace App\Http\Requests;

use App\Models\Cart;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCartRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('cart_create');
    }

    public function rules()
    {
        return [
            'customer_id' => [
                'required',
                'integer',
            ],
			'category_id' => [
                'required',
            ],
			'sub_category_id' => [
                'required',
            ],
			'sales_manager_id' => [
                'required',
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
			'tax_id' => [
                'required',                
            ],
			'is_box' => [
                'required',                
            ],
        ];
    }
}
