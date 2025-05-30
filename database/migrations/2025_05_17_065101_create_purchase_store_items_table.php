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
        Schema::create('purchase_store_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_item_id')->constrained()->onDelete('cascade');

            $table->decimal('qty');
            $table->decimal('rate');
            $table->decimal('sub_total');
            $table->decimal('unit_qty');
            $table->decimal('received')->default(0);
            $table->decimal('discount')->nullable();
            $table->decimal('tax_rate')->nullable();
            $table->decimal('tax_amount')->nullable();
            $table->decimal('total_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_store_items');
    }
};
