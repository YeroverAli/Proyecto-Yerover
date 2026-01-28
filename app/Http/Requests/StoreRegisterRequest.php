<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // registro público
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'empresa_id' => 'required|exists:empresas,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'centro_id' => 'required|exists:centros,id',
            'role' => 'required|string|exists:roles,name',
            'email' => 'required|email|unique:users,email',
            'telefono' => 'nullable|string|max:12',
            'extension' => 'nullable|string|max:10',
            'password' => 'required|confirmed|min:8',
        ];
    }
}