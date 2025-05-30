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
        Schema::create('menu_item_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');

            $table->decimal('qty', 10, 2); // Specify precision
            $table->decimal('sub_total', 10, 2); // price * qty before tax/discount

            $table->decimal('tax_rate', 5, 2)->default(0); // percentage e.g. 7.5
            $table->decimal('tax_amount', 10, 2)->default(0);

            $table->decimal('discount_rate', 5, 2)->default(0); // percentage
            $table->enum('discount_type', ['percentage', 'flat'])->default('percentage');
            $table->decimal('discount_amount', 10, 2)->default(0);

            $table->decimal('total_amount', 10, 2); // amount + tax - discount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_orders');
    }
};
