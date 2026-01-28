<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento; // Asegúrate de usar el namespace correcto

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Departamento::create(['nombre' => 'Ventas', 'abreviatura' => 'V', 'cif' => '376574858']);
        Departamento::create(['nombre' => 'Marketing', 'abreviatura' => 'M', 'cif' => '643574858']);
        Departamento::create(['nombre' => 'IT', 'abreviatura' => 'info', 'cif' => '367324858']);
    }
}
