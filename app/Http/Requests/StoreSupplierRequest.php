<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name'          => ['required', 'string', 'max:255'],
            'contact_person'        => ['nullable', 'string', 'max:255'],
            'email'                 => ['nullable', 'email', 'unique:suppliers,email'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'address'               => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required'         => 'Company name is required',
            'email.unique'                  => 'Email already exists',
            'email.email'                   => 'Invalid email format',
            'phone.max'                     => 'Maximum number exceeded',
        ];
    }
}
