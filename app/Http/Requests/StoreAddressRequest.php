<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
     * @return array{city: string, country: string, postal_code: string, street: string, user_id: string}
     */
    public function rules(): array
    {
        return [
            //
            'user_id'=>'required|exists:users,id',
            'street'=>'required|string',
            'city'=>'required|string',
            'postal_code'=>'required|string',
            'country'=>'required|string',
        ];
    }
}
