<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class GeminiCotizadorService
{
    /**
     * Genera una cotización de logística usando Gemini 2.0 Flash.
     *
     * @param array $datos Datos del formulario de cotización
     * @return array Respuesta estructurada de la IA
     */
    public function cotizar(array $datos): array
    {
        $prompt = $this->construirPrompt($datos);

        try {
            $result = Gemini::geminiFlash()->generateContent($prompt);
            $texto = $result->text();

            // Limpiar posible markdown code block de la respuesta
            $texto = preg_replace('/```json\s*/i', '', $texto);
            $texto = preg_replace('/```\s*/', '', $texto);
            $texto = trim($texto);

            $parsed = json_decode($texto, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($parsed)) {
                Log::warning('Gemini returned non-JSON response', ['response' => $texto]);
                return $this->respuestaFallback();
            }

            return $parsed;

        } catch (\Exception $e) {
            Log::error('Error llamando a Gemini API', [
                'message' => $e->getMessage(),
                'datos' => $datos,
            ]);
            return $this->respuestaFallback();
        }
    }

    private function construirPrompt(array $datos): string
    {
        $tipoCargaMap = [
            'FCL20' => "FCL 20' (contenedor completo 20 pies)",
            'FCL40' => "FCL 40' (contenedor completo 40 pies)",
            'FCL40HC' => "FCL 40' HC (High Cube)",
            'LCL' => 'LCL (carga suelta)',
        ];

        $tipoCarga = $tipoCargaMap[$datos['tipo_carga']] ?? $datos['tipo_carga'];
        $seguro = $datos['requiere_seguro'] ? 'Sí' : 'No';
        $volumen = isset($datos['volumen']) && $datos['volumen'] ? "Volumen: {$datos['volumen']} CBM" : '';
        $valorComercial = isset($datos['valor_comercial']) && $datos['valor_comercial']
            ? "Valor comercial: USD {$datos['valor_comercial']}"
            : 'Valor comercial: No especificado';

        $datosCarga = <<<DATA
        - Puerto de Origen: {$datos['origen']}
        - Puerto de Destino: {$datos['destino']}
        - Tipo de Carga: {$tipoCarga}
        - Peso Total: {$datos['peso']} kg
        {$volumen}
        - Tipo de Mercancía: {$datos['tipo_mercancia']}
        - {$valorComercial}
        - Requiere Seguro de Carga: {$seguro}
        DATA;

        return <<<PROMPT
        Eres un experto en logística internacional y comercio exterior colombiano, especializado en carga marítima FCL y LCL. Trabajas para Caribberan Cargo Agency, un freight forwarder colombiano con operaciones principalmente desde puertos colombianos hacia el mundo.

        El cliente solicita una cotización con estos datos:
        {$datosCarga}

        Genera una cotización estimada profesional en español con:
        1. Rango de tarifa estimada en USD (flete marítimo)
        2. Tiempo estimado de tránsito en días
        3. Navieras recomendadas para esta ruta (2-3 opciones)
        4. Documentos requeridos para esta carga
        5. Alertas importantes (si aplica):
           - Beneficios de Zona Franca de Cartagena
           - Régimen de drawback si corresponde
           - TLCs aplicables Colombia-destino
           - Restricciones o permisos especiales para el tipo de mercancía
        6. Próximos pasos recomendados

        IMPORTANTE: Responde ÚNICAMENTE en formato JSON válido con exactamente estas claves (sin texto adicional fuera del JSON, sin markdown):
        {
          "tarifa_estimada": {
            "minimo": 0,
            "maximo": 0,
            "moneda": "USD",
            "descripcion": "descripción del flete"
          },
          "tiempo_transito": {
            "minimo": 0,
            "maximo": 0,
            "unidad": "días hábiles",
            "descripcion": "descripción del tránsito"
          },
          "navieras": [
            {"nombre": "Naviera", "servicio": "descripción del servicio"}
          ],
          "documentos": ["documento 1", "documento 2"],
          "alertas": [
            {"tipo": "oportunidad|advertencia", "titulo": "título", "descripcion": "descripción"}
          ],
          "proximos_pasos": ["paso 1", "paso 2"]
        }
        PROMPT;
    }

    private function respuestaFallback(): array
    {
        return [
            '_error' => true,
            'mensaje' => 'No fue posible generar la cotización en este momento. Por favor, contáctenos directamente en ingeniero.ambiental@ccargo.co o intente nuevamente en unos minutos.',
        ];
    }
}
