<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CotizacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origen' => ['required', 'string', 'max:200'],
            'destino' => ['required', 'string', 'max:200'],
            'tipo_carga' => ['required', 'in:FCL20,FCL40,FCL40HC,LCL'],
            'peso' => ['required', 'numeric', 'min:0.01', 'max:999999'],
            'volumen' => ['nullable', 'numeric', 'min:0.001', 'max:9999', 'required_if:tipo_carga,LCL'],
            'tipo_mercancia' => ['required', 'string', 'max:500'],
            'valor_comercial' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'requiere_seguro' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'origen.required' => 'Selecciona o ingresa el puerto de origen.',
            'destino.required' => 'Ingresa el puerto de destino.',
            'tipo_carga.required' => 'Selecciona el tipo de carga.',
            'tipo_carga.in' => 'El tipo de carga seleccionado no es válido.',
            'peso.required' => 'Ingresa el peso total de la carga.',
            'peso.numeric' => 'El peso debe ser un número válido.',
            'peso.min' => 'El peso debe ser mayor a 0.',
            'volumen.required_if' => 'El volumen en CBM es requerido para carga LCL.',
            'volumen.numeric' => 'El volumen debe ser un número válido.',
            'tipo_mercancia.required' => 'Describe el tipo de mercancía.',
            'valor_comercial.numeric' => 'El valor comercial debe ser un número válido.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'requiere_seguro' => $this->boolean('requiere_seguro'),
        ]);
    }
}
