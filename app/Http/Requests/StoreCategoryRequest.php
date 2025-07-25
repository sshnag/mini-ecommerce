<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
     * @return array{default_sizes: string, name: string, size_type: string, slug: string}
     */
    public function rules(): array
    {
        return [
            //
            'name'=>'required|string|max:255',
            'slug'=>'required|string|unique:Categories,slug',
            'size_type'=>'required|in:ring,bracelet,none',
            'default_sizes'=>'nullable|array',
        ];
    }
}
