<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcesarPdfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string'],
            'modelo_pdf' => 'required|in:nsmit,subida_dacia,subida_renault'
        ];
    }
}