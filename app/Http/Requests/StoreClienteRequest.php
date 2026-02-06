<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'empresa_id' => 'required|integer|exists:empresas,id',
            'dni' => 'nullable|string|max:10',
            'domicilio' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:5',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|unique:clientes,email',
        ];
    }
}
