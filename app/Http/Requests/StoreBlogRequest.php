<?php

namespace App\Http\Requests;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'excerpt' => ['nullable', 'string', 'min:10', 'max:500'],
            'content' => ['required', 'string', 'min:300', 'max:10000'],
            'category_id' => ['required', Rule::in(Blog::categories())],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'tags' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.min' => 'Title must be at least 5 characters.',
            'excerpt.min' => 'Short description must be at least 10 characters.',
            'content.min' => 'Content must be at least 300 characters long.',
            'content.max' => 'Content must not exceed 10,000 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.in' => 'Invalid category selected.',
            'featured_image.max' => 'Cover image must not exceed 5MB.',
        ];
    }
}
