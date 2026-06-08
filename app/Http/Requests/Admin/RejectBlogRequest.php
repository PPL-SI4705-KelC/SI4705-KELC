<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RejectBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'reject_reason' => ['required', 'string', 'min:5', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'reject_reason.required' => 'A rejection reason is required.',
            'reject_reason.min'      => 'Rejection reason must be at least 5 characters.',
            'reject_reason.max'      => 'Rejection reason must not exceed 500 characters.',
        ];
    }
}
