<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // 1. POST /api/login -> Iniciar sesión
    public function login(Request $request)
    {
        $correo = $request->input('correo');
        $passwordd = $request->input('passwordd');

        // Versión con Procedimiento Almacenado de la BD
        $usuario = DB::selectOne('call login.sp_Usuario_Login(?, ?)', [$correo, $passwordd]);

        if ($usuario) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $usuario->id,
                    'nombres' => $usuario->Nombres,
                    'correo' => $usuario->Correo,
                ],
                'message' => 'Inicio de sesión exitoso'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Credenciales incorrectas'
        ], 401);
    }

    // 2. POST /api/login/registrar -> Guardar o actualizar usuario
    public function registrar(Request $request)
    {
        $nombres = $request->input('nombres');
        $correo = $request->input('correo');
        $passwordd = $request->input('passwordd');

        // Invoca sp_Usuario_Guardar
        DB::statement('call login.sp_Usuario_Guardar(?, ?, ?)', [$nombres, $correo, $passwordd]);

        return response()->json([
            'success' => true,
            'data' => [
                'nombres' => $nombres,
                'correo' => $correo
            ],
            'message' => 'Usuario registrado o actualizado con éxito'
        ], 201);
    }

    // 3. POST /api/login/recuperar -> Generar código y enviar por correo
    public function recuperar(Request $request)
    {
        $correo = $request->input('correo');

        // Verificar si el usuario existe
        $usuario = DB::selectOne('select * from login.Usuario where Correo = ?', [$correo]);

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'El correo no se encuentra registrado'
            ], 404);
        }

        // Generar un código aleatorio de 6 dígitos
        $codigo = (string) rand(100000, 999999);

        // Guardar el código en la base de datos usando el PA
        DB::statement('call login.sp_Usuario_Codigo(?, ?)', [$correo, $codigo]);

        // Intentar enviar el correo si está configurado en .env
        try {
            Mail::raw("Tu código de recuperación para el sistema es: $codigo", function ($message) use ($correo) {
                $message->to($correo)->subject('Código de Recuperación de Contraseña');
            });
        } catch (\Exception $e) {
            // Si el SMTP no está configurado, igual devolvemos el código en la respuesta para pruebas
        }

        return response()->json([
            'success' => true,
            'codigo_generado' => $codigo, // Útil para verificar en Postman/ThunderClient
            'message' => 'Código de recuperación generado y enviado a su correo'
        ], 200);
    }

    // 4. POST /api/login/validar -> Validar código de 6 dígitos
    public function validar(Request $request)
    {
        $correo = $request->input('correo');
        $codigo = $request->input('codigo');

        $valido = DB::selectOne('call login.sp_Usuario_Validar(?, ?)', [$correo, $codigo]);

        if ($valido) {
            return response()->json([
                'success' => true,
                'message' => 'Código válido, proceda a cambiar su contraseña'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Código inválido o expirado'
        ], 400);
    }

    // 5. POST /api/login/passwordd -> Cambiar contraseña
    public function actualizarPassword(Request $request)
    {
        $correo = $request->input('correo');
        $passwordd = $request->input('passwordd');

        DB::statement('call login.sp_Usuario_Update(?, ?)', [$correo, $passwordd]);

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente'
        ], 200);
    }
}