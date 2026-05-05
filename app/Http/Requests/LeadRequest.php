<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:200'],
            'empresa' => ['nullable', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:200'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'cotizacion_id' => ['nullable', 'integer', 'exists:cotizaciones,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Ingresa tu nombre completo.',
            'email.required' => 'Ingresa tu correo electrónico.',
            'email.email' => 'El correo electrónico no es válido.',
            'cotizacion_id.exists' => 'La cotización referenciada no existe.',
        ];
    }
}
