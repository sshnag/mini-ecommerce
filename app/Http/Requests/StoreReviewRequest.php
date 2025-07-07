<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
     * @return array{comment: string, products_id: string, rating: string, user_id: string}
     */
    public function rules(): array
    {
        return [
            //
            'user_id'=>'required|exists:users,id',
            'products_id'=>'required|exists:products,id',
            'rating'=>'required|integer|between:1,5',
            'comment'=>'required|string',
        ];
    }
}
