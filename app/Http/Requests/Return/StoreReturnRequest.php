<?php

namespace App\Http\Requests\Return;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
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
            'return_type' => 'required|in:warranty_claim,recheck,different_issue',
            'return_date' => 'required|date',
            'return_reason' => 'required|string|max:1000',
            'is_same_issue' => 'required|boolean',
            'is_under_warranty' => 'required|boolean',
            'received_by' => 'required|exists:users,id',
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
            'return_type.required' => 'The return type is required.',
            'return_type.in' => 'The return type must be one of: warranty_claim, recheck, different_issue.',
            'return_date.required' => 'The return date is required.',
            'return_date.date' => 'The return date must be a valid date.',
            'return_reason.required' => 'The return reason is required.',
            'return_reason.max' => 'The return reason may not be greater than 1000 characters.',
            'is_same_issue.required' => 'The same issue flag is required.',
            'is_same_issue.boolean' => 'The same issue flag must be true or false.',
            'is_under_warranty.required' => 'The warranty status is required.',
            'is_under_warranty.boolean' => 'The warranty status must be true or false.',
            'received_by.required' => 'The received by user is required.',
            'received_by.exists' => 'The specified user does not exist.',
            'notes.max' => 'The notes may not be greater than 1000 characters.',
        ];
    }
}
