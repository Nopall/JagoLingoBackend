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
        Schema::table('matchs', function (Blueprint $table) {
            $table->dropForeign(['id_player_a']);
            $table->dropForeign(['id_player_b']);
            $table->dropColumn(['id_player_a', 'id_player_b']);
        });

        Schema::table('matchs', function (Blueprint $table) {
            $table->foreignId('id_player_a')->references('id')->on('player_stats')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_player_b')->references('id')->on('player_stats')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matchs', function (Blueprint $table) {
            $table->foreignUuid('id_player_a')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('id_player_b')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }
};
