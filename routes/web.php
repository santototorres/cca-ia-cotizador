<?php

use App\Http\Controllers\CotizadorController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CotizadorController::class, 'index'])->name('cotizador.index');
Route::post('/cotizar', [CotizadorController::class, 'cotizar'])->name('cotizador.cotizar');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
