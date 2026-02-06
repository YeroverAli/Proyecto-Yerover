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
        Schema::table('users', function (Blueprint $table) {
            // Eliminar tabla departamento_id
            $table->dropForeign(['departamento_id']);

            //Hacer columna nullable
            $table->unsignedBigInteger('departamento_id')->nullable()->change();

            //Volver a crear la foreign key
            $table->foreign('departamento_id')->references('id')->on('departamentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']);
            $table->unsignedBigInteger('departamento_id')->nullable(false)->change();
            $table->foreign('departamento_id')->references('id')->on('departamentos');
        });
    }
};
