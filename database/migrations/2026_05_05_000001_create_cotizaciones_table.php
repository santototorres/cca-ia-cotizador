<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('origen');
            $table->string('destino');
            $table->string('tipo_carga'); // FCL20, FCL40, FCL40HC, LCL
            $table->decimal('peso', 10, 2)->nullable();
            $table->decimal('volumen', 10, 3)->nullable(); // CBM, solo LCL
            $table->string('tipo_mercancia');
            $table->decimal('valor_comercial', 14, 2)->nullable();
            $table->boolean('requiere_seguro')->default(false);
            $table->json('respuesta_ia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
