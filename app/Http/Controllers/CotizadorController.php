<?php

namespace App\Http\Controllers;

use App\Http\Requests\CotizacionRequest;
use App\Models\Cotizacion;
use App\Services\GeminiCotizadorService;
use Illuminate\Http\JsonResponse;

class CotizadorController extends Controller
{
    public function __construct(
        private readonly GeminiCotizadorService $geminiService
    ) {}

    /**
     * Mostrar el formulario principal del cotizador.
     */
    public function index()
    {
        return view('cotizador.index');
    }

    /**
     * Procesar la solicitud de cotización con IA.
     */
    public function cotizar(CotizacionRequest $request): JsonResponse
    {
        $datos = $request->validated();

        // Llamar a Gemini para obtener cotización
        $respuestaIa = $this->geminiService->cotizar($datos);

        // Guardar cotización en BD (incluso si Gemini falla, registramos el intento)
        $cotizacion = Cotizacion::create([
            'origen' => $datos['origen'],
            'destino' => $datos['destino'],
            'tipo_carga' => $datos['tipo_carga'],
            'peso' => $datos['peso'],
            'volumen' => $datos['volumen'] ?? null,
            'tipo_mercancia' => $datos['tipo_mercancia'],
            'valor_comercial' => $datos['valor_comercial'] ?? null,
            'requiere_seguro' => $datos['requiere_seguro'] ?? false,
            'respuesta_ia' => $respuestaIa,
        ]);

        // Si Gemini devolvió error, informarlo al frontend
        if (isset($respuestaIa['_error']) && $respuestaIa['_error']) {
            return response()->json([
                'success' => false,
                'mensaje' => $respuestaIa['mensaje'],
                'cotizacion_id' => $cotizacion->id,
            ], 503);
        }

        return response()->json([
            'success' => true,
            'cotizacion_id' => $cotizacion->id,
            'origen' => $datos['origen'],
            'destino' => $datos['destino'],
            'tipo_carga' => $datos['tipo_carga'],
            'resultado' => $respuestaIa,
        ]);
    }
}
