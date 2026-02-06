<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'apellidos',
        'empresa_id',
        'dni',
        'domicilio',
        'codigo_postal',
        'telefono',
        'email',
    ];

        //Relacion entre cliente y empresa, el cliente pertenece a una empresa
        public function empresa()
        {
            return $this->belongsTo(Empresa::class);
        }

        public function ofertas()
        {
            return $this->hasMany(OfertaCabecera::class);
        }
}
