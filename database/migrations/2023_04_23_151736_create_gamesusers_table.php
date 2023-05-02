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
        Schema::create('gamesusers', function (Blueprint $table) {
            $table->id('idGameUser');
            $table->foreignId('idGame')->references('idGame')->on('games');
            $table->foreignId('idPlayer')->references('idUser')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gamesusers');
    }
};
