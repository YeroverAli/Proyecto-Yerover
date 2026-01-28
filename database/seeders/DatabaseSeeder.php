<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            EmpresaSeeder::class,
            DepartamentoSeeder::class,
            CentroSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);

        $user = User::factory()->create([
            'nombre' => 'Brian',
            'apellidos' => 'Ali',
            'empresa_id' => 1,
            'departamento_id' => 1,
            'centro_id' => 1,
            'email' => 'test@example.com',
            'telefono' => '123456789',
            'extension' => '101',
            'password' => '12345678',
        ]);

        $user->assignRole('admin');
    }
}