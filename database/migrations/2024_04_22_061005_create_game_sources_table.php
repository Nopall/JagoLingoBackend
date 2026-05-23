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
        Schema::create('game_sources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('game_id')->references('id')->on('games')->onUpdate('cascade')->onDelete('cascade');
            $table->string('game_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sources');
    }
};
