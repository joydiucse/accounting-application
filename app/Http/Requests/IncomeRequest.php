<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->canManage();
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date|before_or_equal:today',
            'source' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:999999999.99',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'The date field is required.',
            'date.before_or_equal' => 'The date cannot be in the future.',
            'source.required' => 'The income source is required.',
            'amount.required' => 'The amount field is required.',
            'amount.min' => 'The amount must be greater than 0.',
            'amount.max' => 'The amount is too large.',
        ];
    }
}