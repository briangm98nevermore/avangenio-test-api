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
        Schema::create('toro_vaca_games', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->require;
            $table->integer('edad')->require;
            $table->string('token')->unique();
            $table->string('api_key')->unique();
            $table->integer('numeroPropuesto')->nullable();
            $table->integer('numeroIntentos')->nullable();
            $table->boolean('estado')->nullable();
            $table->float('evaluacion')->nullable();
            $table->integer('ranking')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toro_vaca_games');
    }
};
