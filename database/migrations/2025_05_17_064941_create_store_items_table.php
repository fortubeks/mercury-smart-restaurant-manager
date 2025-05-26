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
        Schema::create('store_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_category_id')->nullable()->constrained('store_item_categories')->nullOnDelete(); //eg food, drinks, maintenance
            $table->string('name');
            $table->string('code')->unique();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('unit_measurement');
            $table->decimal('qty', 10, 2)->default(0);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->decimal('low_stock_alert', 10, 2)->nullable();
            $table->boolean('for_sale')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_items');
    }
};
