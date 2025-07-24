<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow authenticated users to update their profile
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Prevent potentially dangerous names
                'regex:/^[\pL\s\-]+$/u'
            ],
            'profile_image' => [
                'nullable',
                File::image()
                    ->max(2048) // 2MB
                    ->dimensions(
                        Rule::dimensions()
                            ->maxWidth(2000)
                            ->maxHeight(2000)
                    )
            ]
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name',
            'name.regex' => 'Name can only contain letters, spaces, and hyphens',
            'profile_image.image' => 'The file must be an image',
            'profile_image.max' => 'The image must not exceed 2MB',
            'profile_image.dimensions' => 'The image dimensions must be less than 2000x2000 pixels',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from name
        $this->merge([
            'name' => trim($this->name)
        ]);
    }

    /**
     * Additional validation after the main rules.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('profile_image')) {
                $file = $this->file('profile_image');

                // Check MIME type
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    $validator->errors()->add(
                        'profile_image',
                        'Invalid image format. Allowed: JPEG, PNG, GIF, WEBP'
                    );
                }
                // Check filename length
                if (strlen($file->getClientOriginalName()) > 100) {
                    $validator->errors()->add(
                        'profile_image',
                        'Filename is too long (max 100 characters)'
                    );
                }
            }
        });
    }
}
