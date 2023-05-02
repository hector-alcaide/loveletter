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
        Schema::create('games', function (Blueprint $table) {
            $table->id('idGame');
            $table->foreignId('idHost')->references('idUser')->on('users');
            $table->enum('type', ['public', 'private']);
            $table->boolean('started')->default('0');
            $table->foreignId('idWinner')->nullable()->references('idUser')->on('users');
            $table->integer('numMaxWins');
            $table->json('game')->nullable();
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
