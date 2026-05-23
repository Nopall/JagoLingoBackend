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
        Schema::create('player_prefs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('id_player')->references('id_player')->on('player_stats')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('prefs_type_id')->references('id')->on('prefs_types')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_prefs');
    }
};
