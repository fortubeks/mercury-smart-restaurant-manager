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
        Schema::create('customer_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_wallet_id')->constrained()->onDelete('cascade');
            $table->string('transaction_type');
            $table->double('amount');
            $table->string('mode_of_payment')->nullable();
            $table->foreignId('incoming_payment_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('outgoing_payment_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_wallet_transactions');
    }
};
