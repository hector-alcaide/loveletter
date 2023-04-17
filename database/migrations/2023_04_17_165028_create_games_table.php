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
            $table->foreignId('idUsuario1')->references('idUsuario')->on('usuarios');
            $table->foreignId('idUsuario2')->references('idUsuario')->on('usuarios');
            $table->foreignId('idUsuario3')->references('idUsuario')->on('usuarios');
            $table->foreignId('idUsuario4')->references('idUsuario')->on('usuarios');
            $table->foreignId('idUsuario5')->references('idUsuario')->on('usuarios');
            $table->foreignId('idUsuario6')->references('idUsuario')->on('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
