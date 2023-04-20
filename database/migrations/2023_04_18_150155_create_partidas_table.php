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
            $table->foreignId('idJugador1')->references('idUsuario')->on('usuarios');
            $table->foreignId('idJugador2')->nullable()->references('idUsuario')->on('usuarios');
            $table->foreignId('idJugador3')->nullable()->references('idUsuario')->on('usuarios');
            $table->foreignId('idJugador4')->nullable()->references('idUsuario')->on('usuarios');
            $table->foreignId('idJugador5')->nullable()->references('idUsuario')->on('usuarios');
            $table->foreignId('idJugador6')->nullable()->references('idUsuario')->on('usuarios');
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
