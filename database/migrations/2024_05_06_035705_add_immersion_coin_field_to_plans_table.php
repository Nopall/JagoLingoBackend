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
        Schema::table(config('laravel-subscriptions.tables.plans'), function (Blueprint $table) {
            $table->decimal('immersion_coin_price')->default('0.00');
            $table->decimal('immersion_coin_signup_fee')->default('0.00');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('laravel-subscriptions.tables.plans'), function (Blueprint $table) {
            $table->dropColumn('immersion_coin_price')->default('0.00');
            $table->dropColumn('immersion_coin_signup_fee')->default('0.00');
        });
    }
};
