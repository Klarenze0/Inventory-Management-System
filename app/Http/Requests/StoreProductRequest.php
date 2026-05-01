<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin()
            || auth()->user()->isInventoryManager();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'           => ['nullable', 'exists:categories,id'],
            'name'                  => ['required', 'string', 'max:255'],
            'sku'                   => ['required', 'string', 'unique:products,sku'],
            'barcode'               => ['nullable', 'string', 'unique:products,barcode'],
            'description'           => ['nullable', 'string'],
            'unit_price'            => ['required', 'numeric', 'min:0'],
            'stock_quantity'        => ['required', 'integer', 'min:0'],
            'low_stock_threshold'   => ['required', 'integer', 'min:0'],
            'image'                 => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => 'Product name is required',
            'sku.required'              => 'SKU is required',
            'sku.unique'                => 'SKU already in use',
            'barcode.unique'            => 'barcode already in use',
            'unit_price.required'       => 'Unit price is required',
            'unit_price.numeric'        => 'Unit price should be a number',
            'stock_quantity.required'   => 'Stock quantity is required',
            'image.image'               => 'You need to upload an image file',
            'image.max'                 => 'Maximum size exceeded | 2 MB',
        ];
    }
}
