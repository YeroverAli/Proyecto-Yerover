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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('chasis');
            $table->string('modelo');
            $table->string('version');
            $table->string('color_externo');
            $table->string('color_interno');
            $table->unsignedBigInteger('empresa_id');
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
