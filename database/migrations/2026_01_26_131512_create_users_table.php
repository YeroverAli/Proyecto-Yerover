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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('apellidos');

            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('departamento_id');
            $table->unsignedBigInteger('centro_id');

            $table->string('email')->unique();
            $table->string('telefono', 12)->nullable();
            $table->string('extension', 10)->nullable();

            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
           
            // Relaciones (foreign keys)
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->foreign('centro_id')->references('id')->on('centros');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
