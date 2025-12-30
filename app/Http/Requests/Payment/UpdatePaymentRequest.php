<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'sometimes|numeric|min:0.01',
            'payment_method' => 'sometimes|in:cash,card,online',
            'status' => 'sometimes|in:pending,completed,failed,refunded',
            'transaction_id' => 'sometimes|string|max:255',
            'notes' => 'sometimes|string|max:1000',
            'is_active' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'amount.min' => 'The amount must be at least 0.01.',
            'payment_method.in' => 'The payment method must be one of: cash, card, online.',
            'status.in' => 'The status must be one of: pending, completed, failed, refunded.',
            'transaction_id.max' => 'The transaction ID may not be greater than 255 characters.',
            'notes.max' => 'The notes may not be greater than 1000 characters.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }
}
