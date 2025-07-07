<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreProductRequest extends FormRequest
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
     * @return array{category_id: string, description: string, image: string, name: string, price: string, stock: string, user_id: string}
     */
    public function rules(): array
    {
        return [
            //
            'user_id'=>'required|exists:users,id',
            'name'=>'required|string:255',
            'description'=>'required|text',
            'price'=>'required|numeric|1000,10000.00|decimal:5,2',
            'stock'=>'required|integer|min:0',
            'image'=>'required|image|mimes:png,jpg|max:20480',
            'category_id' => 'required|exists:categories,id',

        ];
    }
}
