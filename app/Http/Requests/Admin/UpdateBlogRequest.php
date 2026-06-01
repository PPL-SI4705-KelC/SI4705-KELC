<?php

namespace App\Http\Requests\Admin;

use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $action = $this->input('action', 'publish');

        // Draft: only title is required
        if ($action === 'draft') {
            return [
                'title'             => ['required', 'string', 'min:3', 'max:255'],
                'short_description' => ['nullable', 'string', 'max:500'],
                'content'           => ['nullable', 'string'],
                'category'          => ['nullable', Rule::in(Blog::categories())],
                'featured_image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
                'tags'              => ['nullable', 'string', 'max:255'],
            ];
        }

        // Publish: all fields required, but image is optional if already exists
        return [
            'title'             => ['required', 'string', 'min:3', 'max:255'],
            'short_description' => ['required', 'string', 'min:10', 'max:500'],
            'content'           => ['required', 'string', 'min:300'],
            'category'          => ['required', Rule::in(Blog::categories())],
            'featured_image'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
            'tags'              => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Configure the validator instance.
     * On publish: if no featured_image is uploaded AND the blog has no existing image, fail.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $action = $this->input('action', 'publish');

            if ($action === 'publish') {
                $blog = $this->route('blog');

                if (!$this->hasFile('featured_image') && (!$blog || !$blog->featured_image)) {
                    $validator->errors()->add('featured_image', 'A featured image is required to publish.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'title.required'             => 'Blog title is required.',
            'title.min'                  => 'Blog title must be at least 3 characters.',
            'short_description.required' => 'Short description is required to publish.',
            'short_description.min'      => 'Short description must be at least 10 characters.',
            'content.required'           => 'Blog content is required to publish.',
            'content.min'                => 'Blog content must be at least 300 characters.',
            'category.required'          => 'Please select a category.',
            'category.in'               => 'Invalid category selected.',
            'featured_image.image'       => 'Featured image must be a valid image file.',
            'featured_image.mimes'       => 'Featured image must be JPG, PNG, GIF, or WebP.',
            'featured_image.max'         => 'Featured image must not exceed 5MB.',
        ];
    }
}
