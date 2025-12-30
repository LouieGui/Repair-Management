<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Update Customer Request
 *
 * Handles validation for updating existing customers.
 */
class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $customerId = $this->route('customer');

        return [
            'fullname' => ['sometimes', 'string', 'max:255'],
            'contact' => ['sometimes', 'string', 'max:20', 'unique:customers,contact,' . $customerId],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:customers,email,' . $customerId],
            'address' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean']
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact.unique' => 'This contact number is already registered to another customer.',
            'email.unique' => 'This email is already registered to another customer.',
            'email.email' => 'Please provide a valid email address.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Convert empty strings to null for nullable fields
        if ($this->email === '') {
            $this->merge(['email' => null]);
        }

        if ($this->address === '') {
            $this->merge(['address' => null]);
        }
    }
}
