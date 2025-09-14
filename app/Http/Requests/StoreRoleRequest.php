<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:roles,name', 'regex:/^[a-z0-9_]+$/'],
            'display_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da role é obrigatório.',
            'name.unique' => 'Este nome de role já existe.',
            'name.regex' => 'O nome deve conter apenas letras minúsculas, números e underscores.',
            'display_name.required' => 'O nome de exibição é obrigatório.',
            'permissions.*.exists' => 'Uma das permissões selecionadas não existe.',
        ];
    }
}
