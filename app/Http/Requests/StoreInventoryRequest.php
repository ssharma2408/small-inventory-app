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
			'item_name' => [
                'required',
            ],
			'expense_tax' => [
                'numeric',
                'required',
            ],
			'expense_total' => [
                'numeric',
                'required',
            ],
            'discount_type' => [
                'required',
            ],
			'invoice_number' => [
                'required',
            ],
            'discount' => [
                'numeric',
            ],            
            'final_price' => [
                'numeric',
                'required',
            ],
            'days_payable_outstanding' => [
                'required',
            ],            
        ];
    }
}
