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
        Schema::create('store_stock_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_store_item_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_received', 10, 2);
            $table->decimal('quantity_remaining', 10, 2);
            $table->decimal('unit_cost', 10, 2);
            $table->string('batch_reference')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
        Schema::create('outlet_stock_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_stock_batch_id')->constrained('store_stock_batches')->onDelete('cascade');
            $table->decimal('quantity_received', 10, 2);
            $table->decimal('quantity_remaining', 10, 2);
            $table->decimal('unit_cost', 10, 2);
            $table->timestamps();
        });
        Schema::create('menu_item_order_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('outlet_stock_batch_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_item_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_used', 10, 2);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_order_ingredients');
        Schema::dropIfExists('outlet_stock_batches');
        Schema::dropIfExists('store_stock_batches');
    }
};
