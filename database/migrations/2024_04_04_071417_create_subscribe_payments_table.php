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
        Schema::create('subscribe_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('subscribe_transaction_id')->references('id')->on('subscribe_transactions')->onUpdate('cascade')->onDelete('cascade');
            $table->string('amount');
            $table->string('payment_date');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribe_payments');
    }
};
