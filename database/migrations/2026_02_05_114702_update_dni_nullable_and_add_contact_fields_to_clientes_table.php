<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Hacer DNI nullable
            $table->string('dni', 10)->nullable()->change();

            // Nuevos campos
            $table->string('telefono', 20);
            $table->string('email', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Volver DNI obligatorio
            $table->string('dni', 10)->nullable(false)->change();

            // Eliminar campos
            $table->dropColumn(['telefono', 'email']);
        });
    }
};
