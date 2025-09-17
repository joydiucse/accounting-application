<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->canManage();
    }

    public function rules(): array
    {
        $rules = [
            'date' => 'required|date|before_or_equal:today',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01|max:999999999.99',
            'description' => 'nullable|string|max:1000',
            'category_id' => 'nullable|exists:categories,id',
            'from_dollar' => 'boolean',
        ];

        // Add dollar field validation when from_dollar is true
        if ($this->boolean('from_dollar')) {
            $rules['usd_amount'] = 'required|numeric|min:0.01|max:999999999.99';
            $rules['exchange_rate'] = 'required|numeric|min:0.01|max:999.99';
            $rules['bdt_amount'] = 'required|numeric|min:0.01|max:999999999.99';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'date.required' => 'The date field is required.',
            'date.before_or_equal' => 'The date cannot be in the future.',
            'category.required' => 'The category field is required.',
            'amount.required' => 'The amount field is required.',
            'amount.min' => 'The amount must be greater than 0.',
            'amount.max' => 'The amount is too large.',
        ];
    }
}