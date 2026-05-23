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
        Schema::create('war_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('sender_id_player')->references('id_player')->on('player_stats')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('recipient_id_player')->references('id_player')->on('player_stats')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('sender_accept');
            $table->boolean('recipient_accept');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('war_invitations');
    }
};
