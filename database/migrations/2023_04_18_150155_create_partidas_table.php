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
        Schema::create('partidas', function (Blueprint $table) {
            $table->id('idPartida');
            $table->foreignId('idAnfitrion')->references('idUsuario')->on('usuarios');
            $table->enum('tipo', ['publica', 'privada']);
            $table->boolean('empezada')->default('0');
            $table->foreignId('idGanador')->nullable()->references('idUsuario')->on('usuarios');
            $table->integer('numeroVictoriasMaximas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidas');
    }
};
