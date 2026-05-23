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
        Schema::table('subscribe_transactions', function (Blueprint $table) {
            $table->longtext('transaction_data')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscribe_transactions', function (Blueprint $table) {
            $table->string('transaction_data')->change();
        });
    }
};
