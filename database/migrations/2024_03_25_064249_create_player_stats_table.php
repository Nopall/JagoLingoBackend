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
        Schema::create('player_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('id_player')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('player_name');
            $table->integer('currency');
            $table->integer('exp');
            $table->integer('rank_exp');
            $table->foreignId('id_preset')->references('id')->on('preset_teams')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('win');
            $table->integer('lose');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_stats');
    }
};
