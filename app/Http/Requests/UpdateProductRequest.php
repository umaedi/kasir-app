<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $this->route('product'),
            'barcode' => 'nullable|string|unique:products,barcode,' . $this->route('product'),
            'stock' => 'sometimes|required|integer|min:0',
            'min_stock' => 'sometimes|required|integer|min:0',
            'category' => 'sometimes|required|string|max:100',
            'image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'attributes' => 'nullable|array'
        ];
    }
}