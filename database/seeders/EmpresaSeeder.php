<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa; // Asegúrate de usar el namespace correcto

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Empresa::create([
            'nombre' => 'Empresa1',
            'abreviatura' => '1',
            'cif'=> 'B65130643',
            'domicilio'=> 'Urbanizacion Las Torres',
            'telefono'=> '920657432',
        ]);

        Empresa::create([
            'nombre' => 'Grupo ARI',
            'abreviatura' => 'ARI',
            'cif'=> 'B65134233',
            'domicilio'=> 'El Sebadal',
            'telefono'=> '920657416',
        ]);
    }
}