<?php

namespace App\Http\Requests\RepairPart;

use Illuminate\Foundation\Http\FormRequest;

class StoreRepairPartRequest extends FormRequest
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
            'part_id' => 'nullable|exists:parts,id',
            'custom_part_name' => 'required_without:part_id|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'is_approved' => 'nullable|boolean',
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
            'part_id.exists' => 'The specified part does not exist.',
            'custom_part_name.required_without' => 'Custom part name is required when no part ID is provided.',
            'custom_part_name.max' => 'The custom part name may not be greater than 255 characters.',
            'quantity.required' => 'The quantity is required.',
            'quantity.min' => 'The quantity must be at least 1.',
            'unit_price.required' => 'The unit price is required.',
            'unit_price.min' => 'The unit price must be at least 0.',
            'total_price.min' => 'The total price must be at least 0.',
            'notes.max' => 'The notes may not be greater than 1000 characters.',
        ];
    }
}
