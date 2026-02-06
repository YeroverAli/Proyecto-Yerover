<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfertaCabecera extends Model
{
    protected $fillable = [
        'cliente_id',
        'vehiculo_id',
        'fecha',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function lineas()
    {
        return $this->hasMany(OfertaLinea::class);
    }

}
