<?php

use App\Http\Controllers\api\CinestarController;
use Illuminate\Support\Facades\Route;

Route::controller(CinestarController::class)->group( function() {
    Route::get('cines/{id?}', 'cines');
    Route::get('peliculas/{id}', 'peliculas');
});

use App\Http\Controllers\api\OnpeController;

// ==========================================
// RUTAS API ONPE (Elecciones 2016)
// ==========================================

Route::get('participacion/{ambito}', [OnpeController::class, 'participacion']);
Route::get('participacion/{departamento}/{provincia}', [OnpeController::class, 'participacionProvincia']);

Route::get('actas/numero/{numero}', [OnpeController::class, 'actaNumero']);

Route::prefix('actas/ubigeo')->group(function () {
    Route::get('peru', [OnpeController::class, 'ubigeoPeru']);
    Route::get('extranjero', [OnpeController::class, 'ubigeoExtranjero']);
    Route::get('provincia/{id}', [OnpeController::class, 'ubigeoProvincia']);
    Route::get('distrito/{id}', [OnpeController::class, 'ubigeoDistrito']);
    Route::get('locales/{id}', [OnpeController::class, 'ubigeoLocales']);
    Route::get('grupos/{id}', [OnpeController::class, 'ubigeoGrupos']);
});