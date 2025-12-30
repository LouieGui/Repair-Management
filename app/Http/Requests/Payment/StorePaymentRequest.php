<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'repair_id' => 'required|exists:repairs,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,online',
            'status' => 'nullable|in:pending,completed,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
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
            'repair_id.required' => 'The repair ID is required.',
            'repair_id.exists' => 'The specified repair does not exist.',
            'amount.required' => 'The amount is required.',
            'amount.min' => 'The amount must be at least 0.01.',
            'payment_method.required' => 'The payment method is required.',
            'payment_method.in' => 'The payment method must be one of: cash, card, online.',
            'status.in' => 'The status must be one of: pending, completed, failed, refunded.',
            'transaction_id.max' => 'The transaction ID may not be greater than 255 characters.',
            'notes.max' => 'The notes may not be greater than 1000 characters.',
        ];
    }
}
