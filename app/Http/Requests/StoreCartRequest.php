<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
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
     * @return array{product_id: string, quantity: string}
     */
    public function rules(): array
    {
        return [
            //
            'product_id'=>'required|exists:products,id',
            'quantity'=>'required|integer|min:1'
        ];
    }
}
