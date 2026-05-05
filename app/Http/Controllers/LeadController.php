<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    /**
     * Guardar lead de contacto y notificar al equipo.
     */
    public function store(LeadRequest $request): JsonResponse
    {
        $datos = $request->validated();

        $lead = Lead::create($datos);

        // Intentar enviar notificación por email
        try {
            $this->enviarNotificacion($lead);
        } catch (\Exception $e) {
            Log::error('Error enviando email de lead', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
            ]);
            // No retornamos error - el lead ya se guardó en BD
        }

        return response()->json([
            'success' => true,
            'mensaje' => '¡Gracias! Hemos recibido tu solicitud. Un experto de CCA te contactará pronto.',
        ]);
    }

    private function enviarNotificacion(Lead $lead): void
    {
        $destinatario = 'ingeniero.ambiental@ccargo.co';
        $cotizacion = $lead->cotizacion;

        $cuerpo = "Nueva solicitud de cotización formal recibida en CCA IA Cotizador.\n\n";
        $cuerpo .= "--- DATOS DEL CLIENTE ---\n";
        $cuerpo .= "Nombre: {$lead->nombre}\n";
        $cuerpo .= "Empresa: " . ($lead->empresa ?: 'No especificada') . "\n";
        $cuerpo .= "Email: {$lead->email}\n";
        $cuerpo .= "Teléfono: " . ($lead->telefono ?: 'No especificado') . "\n\n";

        if ($cotizacion) {
            $cuerpo .= "--- DATOS DE LA COTIZACIÓN ---\n";
            $cuerpo .= "Origen: {$cotizacion->origen}\n";
            $cuerpo .= "Destino: {$cotizacion->destino}\n";
            $cuerpo .= "Tipo de Carga: {$cotizacion->tipo_carga}\n";
            $cuerpo .= "Mercancía: {$cotizacion->tipo_mercancia}\n";
            $cuerpo .= "Peso: {$cotizacion->peso} kg\n";

            if ($cotizacion->volumen) {
                $cuerpo .= "Volumen: {$cotizacion->volumen} CBM\n";
            }
            if ($cotizacion->valor_comercial) {
                $cuerpo .= "Valor Comercial: USD " . number_format($cotizacion->valor_comercial, 2) . "\n";
            }
            $cuerpo .= "Requiere Seguro: " . ($cotizacion->requiere_seguro ? 'Sí' : 'No') . "\n";
        }

        Mail::raw($cuerpo, function ($message) use ($destinatario, $lead) {
            $message->to($destinatario)
                ->subject("🚢 Nueva Solicitud de Cotización - {$lead->nombre} | CCA IA Cotizador")
                ->from(config('mail.from.address', 'cotizador@ccargo.co'), 'CCA IA Cotizador')
                ->replyTo($lead->email, $lead->nombre);
        });
    }
}
