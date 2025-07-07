<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Summary of rules
     * @return array{order_id: string, price: string, product_id: string, quantity: string}
     */
    public function rules(): array
    {
        return [
            //
            'order_id'=>'required|exists:orders,id',
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|integer|min:1',
            'price'=>'required|numeric|min:0'
        ];
    }
}
