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
        Schema::create('amistad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idUsuario1')->references('idUsuario')->on('usuarios');
            $table->foreignId('idUsuario2')->references('idUsuario')->on('usuarios');
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amistad');
    }
};
