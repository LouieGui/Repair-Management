<?php

namespace App\Http\Requests\Part;

use Illuminate\Foundation\Http\FormRequest;

class StorePartRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:parts,sku',
            'description' => 'nullable|string|max:1000',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
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
            'name.required' => 'The part name is required.',
            'name.max' => 'The part name may not be greater than 255 characters.',
            'sku.required' => 'The SKU is required.',
            'sku.max' => 'The SKU may not be greater than 100 characters.',
            'sku.unique' => 'This SKU is already in use.',
            'description.max' => 'The description may not be greater than 1000 characters.',
            'purchase_price.required' => 'The purchase price is required.',
            'purchase_price.min' => 'The purchase price must be at least 0.',
            'selling_price.required' => 'The selling price is required.',
            'selling_price.min' => 'The selling price must be at least 0.',
            'stock_quantity.required' => 'The stock quantity is required.',
            'stock_quantity.min' => 'The stock quantity must be at least 0.',
        ];
    }
}
