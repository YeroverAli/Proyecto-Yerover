<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Centro; // Asegúrate de usar el namespace correcto

class CentroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Centro::create(['nombre' => 'Centro Principal', 'empresa_id' => 1, 'direccion' => 'Calle Falsa 123', 'provincia' => 'Madrid', 'municipio' => 'Madrid']);
        Centro::create(['nombre' => 'Centro Norte', 'empresa_id' => 1, 'direccion' => 'Avenida Siempreviva 45', 'provincia' => 'Barcelona', 'municipio' => 'Barcelona']);
    }
}
