<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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

        $productId = $this->route('product')->id;

        return [
            'category_id'           => ['nullable', 'exists:categories,id'],
            'name'                  => ['required', 'string', 'max:255'],
            'sku'                   => ['required', 'string', 'unique:products,sku,{$productId}'],
            'barcode'               => ['nullable', 'string', 'unique:products,barcode,{$productId}'],
            'description'           => ['nullable', 'string'],
            'unit_price'            => ['required', 'numeric', 'min:0'],
            'stock_quantity'        => ['required', 'integer', 'min:0'],
            'low_stock_threshold'   => ['required', 'integer', 'min:0'],
            'image'                 => ['nullable', 'image', 'max:2048'],
        ];
    }
}
