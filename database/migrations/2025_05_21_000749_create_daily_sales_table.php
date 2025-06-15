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
        Schema::create('daily_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');

            $table->date('shift_date'); // e.g., 2025-05-16
            $table->string('shift')->nullable(); // e.g., "morning", "evening" if multiple shifts used

            // Sales summaries
            $table->decimal('total', 15, 2)->default(0.00);
            $table->decimal('cash', 15, 2)->default(0.00);
            $table->decimal('pos', 15, 2)->default(0.00);
            $table->decimal('transfer', 15, 2)->default(0.00);
            $table->decimal('wallet', 15, 2)->default(0.00);
            $table->decimal('credit', 15, 2)->default(0.00); // optional
            $table->decimal('discounts', 15, 2)->default(0.00);
            $table->decimal('tax', 15, 2)->default(0.00);

            // Meta
            $table->decimal('opening_balance', 15, 2)->nullable(); // cash in till at start of shift
            $table->decimal('closing_balance', 15, 2)->nullable(); // actual cash counted at close
            $table->decimal('expected_cash_balance', 15, 2)->nullable(); // auto-calculated: opening + cash_sales - outflow
            $table->decimal('cash_outflow', 15, 2)->nullable(); // cash outflow

            $table->foreignId('audited_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sales');
    }
};
