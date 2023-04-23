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
        Schema::create('partidasusuarios', function (Blueprint $table) {
            $table->id('idPartidaUsuario');
            $table->foreignId('idPartida')->references('idPartida')->on('partidas');
            $table->foreignId('idJugador')->references('idUsuario')->on('usuarios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidasusuarios');
    }
};
