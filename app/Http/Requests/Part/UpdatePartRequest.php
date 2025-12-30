<?php

namespace App\Http\Requests\Part;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartRequest extends FormRequest
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
        $partId = $this->route('part');

        return [
            'name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|max:100|unique:parts,sku,' . $partId,
            'description' => 'sometimes|string|max:1000',
            'purchase_price' => 'sometimes|numeric|min:0',
            'selling_price' => 'sometimes|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
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
            'name.max' => 'The part name may not be greater than 255 characters.',
            'sku.max' => 'The SKU may not be greater than 100 characters.',
            'sku.unique' => 'This SKU is already in use.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'purchase_price.min' => 'The purchase price must be at least 0.',
            'selling_price.min' => 'The selling price must be at least 0.',
            'stock_quantity.min' => 'The stock quantity must be at least 0.',
            'is_active.boolean' => 'The active status must be true or false.',
        ];
    }
}
