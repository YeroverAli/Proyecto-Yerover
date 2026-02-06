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
        Schema::create('oferta_cabeceras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('vehiculo_id');
            $table->dateTime('fecha');
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->cascadeOnDelete();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta_cabeceras');
    }
};
