<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     * @return array{address_id: string, status: string, total_amount: string, user_id: string}
     */
    public function rules(): array
    {
        return [
            //
            'user_id'=>'required|exists:users,id',
            'address_id'=>'required|exists:addresses,id',
            'total_amount'=> 'required|numeric|min:0',
            'status'=>'required|in:pending,paid,shipped, cancelled',
        ];
    }
}
