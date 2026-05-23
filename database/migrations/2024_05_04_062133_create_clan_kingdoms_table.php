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
        Schema::create('clan_kingdoms', function (Blueprint $table) {
            $table->id();
            $table->string('id_kingdom', 6);
            $table->string('name_kingdom');
            $table->foreignUuid('created_by')->references('id_player')->on('player_stats')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clan_kingdoms');
    }
};
