<?php

namespace App\Http\Requests\Repair;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Store Repair Request
 *
 * Handles validation for creating new repairs.
 */
class StoreRepairRequest extends FormRequest
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
            'device_id' => ['required', 'integer', 'exists:devices,id'],
            'technician_id' => ['nullable', 'integer', 'exists:users,id'],
            'reported_issue' => ['required', 'string'],
            'accessories' => ['nullable', 'string'],
            'status' => ['nullable', 'string', Rule::in([
                'received', 'diagnosing', 'waiting_approval', 'approved',
                'waiting_parts', 'in_progress', 'testing', 'ready_for_pickup',
                'completed', 'cancelled'
            ])],
            'diagnosis' => ['nullable', 'string'],
            'technician_notes' => ['nullable', 'string'],
            'date_received' => ['nullable', 'date'],
            'estimated_completion_date' => ['nullable', 'date', 'after:date_received'],
            'date_completed' => ['nullable', 'date'],
            'warranty_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'warranty_until_date' => ['nullable', 'date', 'after:date_completed'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0', 'max:total_amount'],
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
            'device_id.required' => 'The device ID is required.',
            'device_id.exists' => 'The selected device does not exist.',
            'technician_id.exists' => 'The selected technician does not exist.',
            'reported_issue.required' => 'The reported issue is required.',
            'status.in' => 'The selected status is invalid.',
            'estimated_completion_date.after' => 'Estimated completion date must be after date received.',
            'warranty_until_date.after' => 'Warranty until date must be after completion date.',
            'paid_amount.max' => 'Paid amount cannot exceed total amount.',
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
        if ($this->diagnosis === '') {
            $this->merge(['diagnosis' => null]);
        }

        if ($this->technician_notes === '') {
            $this->merge(['technician_notes' => null]);
        }

        if ($this->accessories === '') {
            $this->merge(['accessories' => null]);
        }
    }
}
