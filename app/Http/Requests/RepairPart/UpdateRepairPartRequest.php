<?php

namespace App\Http\Requests\RepairPart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRepairPartRequest extends FormRequest
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
            'part_id' => 'sometimes|exists:parts,id',
            'custom_part_name' => 'sometimes|string|max:255',
            'quantity' => 'sometimes|integer|min:1',
            'unit_price' => 'sometimes|numeric|min:0',
            'total_price' => 'sometimes|numeric|min:0',
            'is_approved' => 'sometimes|boolean',
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
            'part_id.exists' => 'The specified part does not exist.',
            'custom_part_name.max' => 'The custom part name may not be greater than 255 characters.',
            'quantity.min' => 'The quantity must be at least 1.',
            'unit_price.min' => 'The unit price must be at least 0.',
            'total_price.min' => 'The total price must be at least 0.',
            'notes.max' => 'The notes may not be greater than 1000 characters.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }
}
