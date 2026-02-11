<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $fillable = [
        'bastidor',
        'referencia',
        'modelo',
        'version',
        'color_externo',
        'color_interno',
        'empresa_id'
    ];

    //Relacion entre vehiculo y empresa, el vehiculo pertenece a una empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function ofertas()
    {
        return $this->hasMany(OfertaCabecera::class);
    }
}
