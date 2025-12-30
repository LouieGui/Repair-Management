<?php

namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Device Request
 *
 * Handles validation for creating new devices.
 */
class StoreDeviceRequest extends FormRequest
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
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100', 'unique:devices,serial_number'],
            'imei' => ['nullable', 'string', 'max:20', 'unique:devices,imei'],
            'device_warranty_status' => ['nullable', 'string', Rule::in(['active', 'expired', 'none'])],
            'device_password' => ['nullable', 'string', 'max:100'],
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
            'customer_id.required' => 'The customer ID is required.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'brand.required' => 'The device brand is required.',
            'model.required' => 'The device model is required.',
            'serial_number.unique' => 'This serial number is already registered.',
            'imei.unique' => 'This IMEI is already registered.',
            'device_warranty_status.in' => 'The warranty status must be active, expired, or none.',
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
        if ($this->serial_number === '') {
            $this->merge(['serial_number' => null]);
        }

        if ($this->imei === '') {
            $this->merge(['imei' => null]);
        }

        if ($this->device_password === '') {
            $this->merge(['device_password' => null]);
        }
    }
}
