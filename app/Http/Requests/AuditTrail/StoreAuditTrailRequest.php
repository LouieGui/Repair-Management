<?php

namespace App\Http\Requests\AuditTrail;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuditTrailRequest extends FormRequest
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
            'user_id' => 'nullable|exists:users,id',
            'user_type' => 'required|string|max:50',
            'audit_table' => 'required|string|max:50',
            'event' => 'required|string|max:50',
            'old_values' => 'nullable|string',
            'new_values' => 'nullable|string',
            'audited_at' => 'nullable|date',
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
            'user_id.exists' => 'The specified user does not exist.',
            'user_type.required' => 'The user type is required.',
            'user_type.max' => 'The user type may not be greater than 50 characters.',
            'audit_table.required' => 'The audit table is required.',
            'audit_table.max' => 'The audit table may not be greater than 50 characters.',
            'event.required' => 'The event is required.',
            'event.max' => 'The event may not be greater than 50 characters.',
            'audited_at.date' => 'The audited at must be a valid date.',
        ];
    }
}
