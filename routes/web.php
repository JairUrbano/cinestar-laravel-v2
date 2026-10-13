<?php

use App\Http\Controllers\web\CinestarController;
use Illuminate\Support\Facades\Route;

Route::controller(CinestarController::class)->group( function() {
    Route::get('/', 'index')->name('/');
    Route::get('cines/{id?}', 'cines')->name('cines');
    Route::get('peliculas/{id}', 'peliculas')->name('peliculas');
});
