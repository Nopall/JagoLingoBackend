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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('transaction_code', 12);
            $table->string('transaction_type');
            $table->string('amount');
            $table->foreignUuid('topup_package_id')->references('id')->on('topup_packages');
            $table->string('email');
            $table->foreignUuid('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('pdf_url');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
