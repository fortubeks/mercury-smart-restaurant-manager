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
        Schema::create('item_migration_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_migration_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_item_id')->constrained()->onDelete('cascade');
            $table->decimal('qty', 10, 2);
            $table->decimal('from_balance', 10, 2);
            $table->decimal('to_balance', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_migration_details');
    }
};
