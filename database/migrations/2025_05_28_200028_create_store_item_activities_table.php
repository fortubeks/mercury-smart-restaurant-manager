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
        Schema::create('store_item_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('store_issue_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('activity_date');
            $table->string('description');
            $table->decimal('previous_qty', 10, 2);
            $table->decimal('qty', 10, 2);
            $table->decimal('current_qty', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_item_activities');
    }
};
