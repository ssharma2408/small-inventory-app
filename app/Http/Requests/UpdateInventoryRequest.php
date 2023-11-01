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
        //return Gate::allows('inventory_edit');
        return true;
    }

    public function rules()
    {
        return [            
            'discount_type' => [
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
