<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'Usuario';
    protected $primaryKey = 'IdUsuario';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'Nombres',
        'Apellidos',
        'Dni',
        'FechaNacimiento',
        'Edad',
        'Telefono',
        'Correo',
        'Passwordd',
        'codigo_respaldo',
        'codigo_recuperacion',
    ];

    protected $hidden = [
        'Passwordd',
    ];
}