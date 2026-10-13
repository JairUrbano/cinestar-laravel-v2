<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CinestarController extends Controller
{
    public function index(){
        return view('index');
    }

    public function cines($id=null) {
        if ( $id == null ) $cines = DB::select('call sp_getCines');
        else if ( is_numeric($id) ) {
            $cine = DB::selectOne('call sp_getCine(?)', [$id]);
            $peliculas = DB::select('call sp_getCinePeliculas(?)', [$id]);
            $tarifas = DB::select('call sp_getCineTarifas(?)', [$id]);

            if ( isset($cine) )
            $cine = [
                'cine' => $cine,
                'peliculas' => $peliculas,
                'tarifas' => $tarifas
            ];
        }

        if ( isset($cines) ) return view ('cines', compact('cines'));
        else if ( isset($cine) ) return view('cine', compact('cine'));

        return view('index');
    }

    public function peliculas($id) {
        if ( $id == 'cartelera' || $id == 'estrenos' ) {
            $id = $id == 'cartelera' ? 1 : 2;
            $peliculas = DB::select('call sp_getPeliculas(?)', [$id]);
        } else
            if ( is_numeric($id) )
                $pelicula = DB::selectOne('call sp_getPelicula(?)', [$id]);

        if ( isset($peliculas) ) return view ('peliculas', compact('peliculas'));
        else if ( isset($pelicula) ) return view('pelicula', compact('pelicula'));

        return view('index');
    }
}
