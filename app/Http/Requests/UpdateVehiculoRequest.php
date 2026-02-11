<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehiculoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * La autorización se maneja en el controlador mediante VehiculoPolicy.
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
            'bastidor' => 'nullable|string|max:17',
            'referencia' => 'nullable|string|max:255',
            'modelo' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'color_externo' => 'nullable|string|max:255',
            'color_interno' => 'nullable|string|max:255',
            'empresa_id' => 'required|exists:empresas,id',
        ];
    }
}
