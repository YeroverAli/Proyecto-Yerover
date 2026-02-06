<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * La autorización se maneja en el controlador mediante UserPolicy.
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
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'empresa_id' => 'required|integer|exists:empresas,id',
            'departamento_id' => 'required|integer|exists:departamentos,id',
            'centro_id' => 'required|integer|exists:centros,id',
            'role' => 'required|string|exists:roles,name',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'nullable|string|max:12',
            'extension' => 'nullable|string|max:10',
            'password' => 'required|min:8|confirmed',
        ];
    }
}
