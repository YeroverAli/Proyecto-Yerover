<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;

class Centro extends Model
{
    protected $fillable = [
    'nombre',
    'empresa_id',
    'direccion',
    'provincia',
    'municipio',

    ];

        //Relacion uno a muchos: un centro puede tener multiples usuarios.
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
