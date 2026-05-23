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
        Schema::create('matchs', function (Blueprint $table) {
            $table->id();
            $table->string('id_room');
            $table->foreignUuid('id_player_a')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('id_player_b')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('score_a');
            $table->integer('score_b');
            $table->string('player_disconnect_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matchs');
    }
};
