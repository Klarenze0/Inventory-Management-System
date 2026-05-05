<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreStockMovementRequest extends FormRequest
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
        $type = $this->input('type');
        
        return [
            'product_id'    => ['required', 'exists:products,id'],
            'type'          => ['required', 'in:in,out,adjustment'],
            'quantity'      => ['required', 'integer', 'not_in:0', $type === 'adjustment' ? 'min:-99999' : 'min:1'],
            'reason'        => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required'       => 'You need to select a product',
            'product_id.exists'         => 'Cannot find product',
            'type.required'             => 'Type of movement required',
            'type.in'                   => 'in, out, or adjustment for the type of movement',
            'quantity.required'         => 'Quantity is required',
            'quantity.integer'          => 'Quantity has to be a number',
            'quantity.not_in'           => 'Quantity cannot be 0',
        ];
    }
}
