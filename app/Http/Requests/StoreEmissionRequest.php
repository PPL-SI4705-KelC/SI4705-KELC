<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transport' => ['required', 'array'],
            'transport.*' => ['nullable', 'string'],
            
            'consumption' => ['required', 'array'],
            'consumption.*' => ['nullable', 'string'],
            
            'energy' => ['required', 'array'],
            'energy.*' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'transport.required' => 'Transport data is required.',
            'consumption.required' => 'Consumption data is required.',
            'energy.required' => 'Energy data is required.',
        ];
    }
}
