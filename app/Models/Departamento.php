<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Departamento extends Model
{
    protected $fillable = [
        'nombre',
        'abreviatura',
        'cif',
        ];

    //Relacion uno a muchos: un departamento puede tener multiples usuarios.
    public function users()
    {
        return $this->hasMany(User::class);
    }
}

