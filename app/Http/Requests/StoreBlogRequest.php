<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'min:50'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.min' => 'Title must be at least 5 characters.',
            'content.min' => 'Content must be at least 50 characters long.',
            'cover_image.max' => 'Cover image must not exceed 2MB.',
        ];
    }
}
