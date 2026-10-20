<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'login.Usuario'; // Conecta directo a la BD Login
    protected $primaryKey = 'id';
    public $timestamps = false; // La tabla usa create_at y update_at con default

    protected $fillable = [
        'Nombres',
        'Correo',
        'Passwordd',
    ];

    // Ocultar la contraseña en las respuestas JSON
    protected $hidden = [
        'Passwordd',
    ];
}
