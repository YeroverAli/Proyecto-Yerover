<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Add this line

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('oferta_lineas', function (Blueprint $table) {
            $table->string('descripcion')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Update NULL descriptions before changing column to NOT NULL
        DB::table('oferta_lineas')->whereNull('descripcion')->update(['descripcion' => 'Sin descripción']);

        Schema::table('oferta_lineas', function (Blueprint $table) {
            $table->string('descripcion')->nullable(false)->change();
        });
    }
};