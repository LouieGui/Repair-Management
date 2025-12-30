<?php

namespace App\Http\Requests\Return;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReturnRequest extends FormRequest
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
            'return_type' => 'sometimes|in:warranty_claim,recheck,different_issue',
            'return_date' => 'sometimes|date',
            'return_reason' => 'sometimes|string|max:1000',
            'is_same_issue' => 'sometimes|boolean',
            'is_under_warranty' => 'sometimes|boolean',
            'received_by' => 'sometimes|exists:users,id',
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
            'return_type.in' => 'The return type must be one of: warranty_claim, recheck, different_issue.',
            'return_date.date' => 'The return date must be a valid date.',
            'return_reason.max' => 'The return reason may not be greater than 1000 characters.',
            'is_same_issue.boolean' => 'The same issue flag must be true or false.',
            'is_under_warranty.boolean' => 'The warranty status must be true or false.',
            'received_by.exists' => 'The specified user does not exist.',
            'notes.max' => 'The notes may not be greater than 1000 characters.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }
}
