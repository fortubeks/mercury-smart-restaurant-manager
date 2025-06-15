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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users'); // The staff handling the order
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');;
            $table->foreignId('delivery_area_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('served_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('order_date'); //shift
            $table->string('status')->default('settled'); //open,settled
            $table->string('payment_status')->default('pending');
            $table->string('reference')->unique()->nullable();
            $table->decimal('sub_total', 10, 2);
            $table->decimal('tax_rate')->default(0.00);
            $table->decimal('tax_amount')->default(0.00);
            $table->decimal('discount_rate')->default(0.00);
            $table->string('discount_type')->default('flat');
            $table->decimal('discount_amount')->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->string('delivery_address')->nullable(); // Free-text address
            $table->foreignId('delivery_rider_id')->nullable()->constrained('delivery_riders')->onDelete('set null');
            $table->decimal('delivery_fee', 8, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
