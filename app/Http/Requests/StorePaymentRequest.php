<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
     * @return array{method: string, order_id: string, status: string, transaction_id: string}
     */
    public function rules(): array
    {
        return [
            //
            'order_id'=>'required|exists:orders,id',
            'method'=>'required|in:paypal,card, cod',
            'status'=>'required|in:paid,,failed, pending',
            'transaction_id'=>'nullable|string',
        ];
    }
}
