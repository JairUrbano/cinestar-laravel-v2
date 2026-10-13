<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CinestarController extends Controller
{
    public function cines($id=null) {
        if ( $id == null ) $cines = DB::select('call sp_getCines');
        else if ( is_numeric($id) ) {
            $cines = DB::selectOne('call sp_getCine(?)', [$id]);
            $peliculas = DB::select('call sp_getCinePeliculas(?)', [$id]);
            $tarifas = DB::select('call sp_getCineTarifas(?)', [$id]);

            if ( isset($cines) )
            $cines = [
                'cine' => $cines,
                'peliculas' => $peliculas,
                'tarifas' => $tarifas
            ];
        }

        $success = isset($cines);
        $status = $success ? 200 : 404;
        $cines = [
            'success' => $success,
            'data' => $success ? $cines : null,
            'message' => $success ? "Registros encontrados" : "Registros no encontrados"
        ];

        return response()->json($cines, $status);
    }

    public function peliculas($id) {
        if ( $id == 'cartelera' || $id == 'estrenos' ) {
            $id = $id == 'cartelera' ? 1 : 2;
            $peliculas = DB::select('call sp_getPeliculas(?)', [$id]);
        } else
            if ( is_numeric($id) )
                $peliculas = DB::selectOne('call sp_getPelicula(?)', [$id]);

        $success = isset($peliculas);
        $status = $success ? 200 : 404;
        $peliculas = [
            'success' => $success,
            'data' => $success ? $peliculas : null,
            'message' => $success ? "Registros encontrados" : "Registros no encontrados"
        ];

        return response()->json($peliculas, $status);
    }
}