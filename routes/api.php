<?php

use App\Http\Controllers\api\CinestarController;
use Illuminate\Support\Facades\Route;

Route::controller(CinestarController::class)->group( function() {
    Route::get('cines/{id?}', 'cines');
    Route::get('peliculas/{id}', 'peliculas');
});