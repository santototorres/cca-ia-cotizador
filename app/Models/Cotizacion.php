<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'origen',
        'destino',
        'tipo_carga',
        'peso',
        'volumen',
        'tipo_mercancia',
        'valor_comercial',
        'requiere_seguro',
        'respuesta_ia',
    ];

    protected $casts = [
        'respuesta_ia' => 'array',
        'requiere_seguro' => 'boolean',
        'peso' => 'decimal:2',
        'volumen' => 'decimal:3',
        'valor_comercial' => 'decimal:2',
    ];

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
