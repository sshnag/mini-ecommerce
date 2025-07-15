<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Make sure this is true
    }

    public function rules(): array
    {
        return [
            'user_id'      => 'required|exists:users,id',
            'name'         => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric|min:0|max:10000',
            'stock'        => 'required|integer|min:0',
            'image'        => 'required|image|mimes:jpg,jpeg,png|max:20480',
            'category_id'  => 'required|exists:categories,id',


        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $category = \App\Models\Category::find($this->category_id);

            if ($category && $category->size_type !== 'none') {
                $allowedSizes = $category->default_sizes;

                foreach ($this->input('available_sizes', []) as $size)
 {
                    if (!in_array($size, $allowedSizes)) {
                        $validator->errors()->add('sizes', "Invalid size '$size' for selected category.");
                    }
                }
            }
        });
    }
}
