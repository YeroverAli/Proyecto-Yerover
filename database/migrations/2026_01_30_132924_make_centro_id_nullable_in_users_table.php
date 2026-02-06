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
            // Eliminar tabla centro_id
            $table->dropForeign(['centro_id']);

            //Hacer columna nullable
            $table->unsignedBigInteger('centro_id')->nullable()->change();

            //Volver a crear la foreign key
            $table->foreign('centro_id')->references('id')->on('centros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['centro_id']);
            $table->unsignedBigInteger('centro_id')->nullable(false)->change();
            $table->foreign('centro_id')->references('id')->on('centros');
        });
    }
};
