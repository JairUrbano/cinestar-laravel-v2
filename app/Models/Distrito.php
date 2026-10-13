<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distrito extends Model
{
    use HasFactory;

    protected $table = 'Distrito';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'idCiudad',
        'Detalle',
    ];

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'idCiudad', 'id');
    }

    public function cines()
    {
        return $this->hasMany(Cine::class, 'idDistrito', 'id');
    }
}
