<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfertaLinea extends Model
{
    protected $fillable = [
        'oferta_cabecera_id',
        'tipo',
        'descripcion',
        'precio',
    ];

    public function oferta()
    {
        return $this->belongsTo(OfertaCabecera::class, 'oferta_cabecera_id');
    }
}
