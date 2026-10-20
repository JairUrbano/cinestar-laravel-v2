<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OnpeController extends Controller
{
    // ==========================================
    // 1. PARTICIPACIÓN CIUDADANA
    // ==========================================

    // GET /api/participacion/{ambito} -> nacional, extranjero o departamento (ej: AMAZONAS)
    public function participacion($ambito)
    {
        $ambitoLower = strtolower($ambito);

        if ($ambitoLower === 'nacional') {
            $data = DB::select('call onpe.sp_getVotos(1, 25)');
        } elseif ($ambitoLower === 'extranjero') {
            $data = DB::select('call onpe.sp_getVotos(26, 30)');
        } else {
            // Nombre del departamento (ej: AMAZONAS)
            $data = DB::select('call onpe.sp_getVotosDepartamento(?)', [strtoupper($ambito)]);
        }

        return $this->respuestaJson($data);
    }

    // GET /api/participacion/{departamento}/{provincia} (ej: /AMAZONAS/BAGUA)
    public function participacionProvincia($departamento, $provincia)
    {
        $data = DB::select('call onpe.sp_getVotosProvincia(?)', [strtoupper($provincia)]);
        return $this->respuestaJson($data);
    }

    // ==========================================
    // 2. ACTAS
    // ==========================================

    // GET /api/actas/numero/{numero} (código de 6 dígitos, ej: 000001)
    public function actaNumero($numero)
    {
        $data = DB::select('call onpe.sp_getGrupoVotacion(?)', [$numero]);
        return $this->respuestaJson($data);
    }

    // ==========================================
    // 3. UBIGEO
    // ==========================================

    // GET /api/actas/ubigeo/peru
    public function ubigeoPeru()
    {
        $data = DB::select('call onpe.sp_getDepartamentos(1, 25)');
        return $this->respuestaJson($data);
    }

    // GET /api/actas/ubigeo/extranjero
    public function ubigeoExtranjero()
    {
        $data = DB::select('call onpe.sp_getDepartamentos(26, 30)');
        return $this->respuestaJson($data);
    }

    // GET /api/actas/ubigeo/provincia/{id} (acepta ID numérico o nombre)
    public function ubigeoProvincia($id)
    {
        if (is_numeric($id)) {
            $data = DB::select('call onpe.sp_getProvincias(?)', [$id]);
        } else {
            $data = DB::select('call onpe.sp_getProvinciasbyDepartamento(?)', [strtoupper($id)]);
        }
        return $this->respuestaJson($data);
    }

    // GET /api/actas/ubigeo/distrito/{id}
    public function ubigeoDistrito($id)
    {
        if (is_numeric($id)) {
            $data = DB::select('call onpe.sp_getDistritos(?)', [$id]);
        } else {
            $data = DB::select('call onpe.sp_getDistritosByProvincia(?)', [strtoupper($id)]);
        }
        return $this->respuestaJson($data);
    }

    // GET /api/actas/ubigeo/locales/{idDistrito}
    public function ubigeoLocales($id)
    {
        $data = DB::select('call onpe.sp_getLocalesVotacion(?)', [$id]);
        return $this->respuestaJson($data);
    }

    // GET /api/actas/ubigeo/grupos/{idLocal}
    public function ubigeoGrupos($id)
    {
        $data = DB::select('call onpe.sp_getGruposVotacion(?)', [$id]);
        return $this->respuestaJson($data);
    }

    // ==========================================
    // FORMATO DE RESPUESTA JSON ESTÁNDAR
    // ==========================================
    private function respuestaJson($data)
    {
        $success = !empty($data);
        $status = $success ? 200 : 404;

        return response()->json([
            'success' => $success,
            'data' => $success ? $data : null,
            'message' => $success ? 'Registros encontrados' : 'Registros no encontrados'
        ], $status);
    }
}