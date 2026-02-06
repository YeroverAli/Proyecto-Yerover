<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehiculoRequest extends FormRequest
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
            'chasis' => 'required|string|max:17',
            'modelo' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'bastidor' => 'nullable|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'color_externo' => 'nullable|string|max:255',
            'color_interno' => 'nullable|string|max:255',
            'empresa_id' => 'required|integer|exists:empresas,id',
        ];
    }
}
