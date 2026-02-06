<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Empresa;
use App\Models\Departamento;
use App\Models\Centro;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'empresa_id',
        'departamento_id',
        'centro_id',
        'email',
        'telefono',
        'extension',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //Relacion entre usuario y empresa, el usuario pertenece a una empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    //Relacion entre usuario y departamento, el usuario pertenece a un departamento
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);

    }

    //Relacion entre usuario y centro, el usuario pertenece a un centro
    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    /**
     * Verifica si el usuario es administrador (por ID del rol, no por nombre)
     * Usa la configuración de config/roles.php para obtener el ID del admin
     */
    public function isAdmin(): bool
    {
        $adminRoleId = config('roles.admin_role_id', 1);
        $adminRole = \Spatie\Permission\Models\Role::find($adminRoleId);
        return $adminRole && $this->hasRole($adminRole);
    }
}
