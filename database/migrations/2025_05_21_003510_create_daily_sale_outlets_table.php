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
        Schema::create('daily_sale_outlets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('outlet_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 15, 2)->default(0.00);
            $table->decimal('cash', 15, 2)->default(0.00);
            $table->decimal('pos', 15, 2)->default(0.00);
            $table->decimal('transfer', 15, 2)->default(0.00);
            $table->decimal('wallet', 15, 2)->default(0.00);
            $table->decimal('credit', 15, 2)->default(0.00); // optional
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sale_outlets');
    }
};
