<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartamentoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * La autorización se maneja en el controlador mediante DepartamentoPolicy.
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
            'abreviatura' => 'required|string|max:10',
            'cif' => 'required|string|max:10',
        ];
    }
}
