<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartamentoRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'nombre'      => ['required', 'string', 'max:255'],
            'abreviatura' => ['required', 'string', 'max:50'],
            'cif'         => ['required', 'string', 'max:20'],
        ];
    }
}