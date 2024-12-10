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
            $table->string('token')->unique();
            $table->string('api_key')->unique();
            $table->integer('numeroPropuesto')->nullable();
            $table->integer('numeroIntentos')->nullable();
            $table->boolean('estado')->nullable();
            $table->float('evaluacion')->nullable();
            $table->integer('ranking')->nullable();

            $table->unsignedBigInteger('user_id')
            ->constrained()
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');

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
