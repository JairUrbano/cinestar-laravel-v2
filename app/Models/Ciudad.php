<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ciudad extends Model
{
    use HasFactory;

    protected $table = 'Ciudad';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'Detalle',
    ];

    public function distritos()
    {
        return $this->hasMany(Distrito::class, 'idCiudad', 'id');
    }

    public function cines()
    {
        return $this->hasManyThrough(
            Cine::class,
            Distrito::class,
            'idCiudad',   // FK en Distrito
            'idDistrito', // FK en Cine
            'id',         // PK de Ciudad
            'id'          // PK de Distrito
        );
    }
}
