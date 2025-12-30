<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Store Customer Request
 *
 * Handles validation for creating new customers.
 */
class StoreCustomerRequest extends FormRequest
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
        return [
            'fullname' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:20', 'unique:customers,contact'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:customers,email'],
            'address' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean']
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
            'fullname.required' => 'The customer name is required.',
            'contact.required' => 'The contact number is required.',
            'contact.unique' => 'This contact number is already registered.',
            'email.unique' => 'This email is already registered.',
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
