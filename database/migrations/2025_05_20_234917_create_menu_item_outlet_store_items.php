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
        // Schema::create('menu_item_outlet_store_items', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('outlet_store_item_id')->constrained()->onDelete('cascade');
        //     $table->decimal('quantity_used', 10, 2); // how much of the store item is used per menu item sold
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('menu_item_outlet_store_items');
    }
};
