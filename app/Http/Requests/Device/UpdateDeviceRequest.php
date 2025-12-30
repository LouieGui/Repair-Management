<?php

namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update Device Request
 *
 * Handles validation for updating existing devices.
 */
class UpdateDeviceRequest extends FormRequest
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
        $deviceId = $this->route('device');

        return [
            'customer_id' => ['sometimes', 'integer', 'exists:customers,id'],
            'brand' => ['sometimes', 'string', 'max:100'],
            'model' => ['sometimes', 'string', 'max:100'],
            'serial_number' => ['sometimes', 'string', 'max:100', 'unique:devices,serial_number,' . $deviceId],
            'imei' => ['sometimes', 'string', 'max:20', 'unique:devices,imei,' . $deviceId],
            'device_warranty_status' => ['sometimes', 'string', Rule::in(['active', 'expired', 'none'])],
            'device_password' => ['nullable', 'string', 'max:100'],
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
            'customer_id.exists' => 'The selected customer does not exist.',
            'serial_number.unique' => 'This serial number is already registered to another device.',
            'imei.unique' => 'This IMEI is already registered to another device.',
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
