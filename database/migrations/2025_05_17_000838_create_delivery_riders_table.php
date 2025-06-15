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
        Schema::create('delivery_riders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('status')->default('active'); // e.g., active, inactive, busy
            $table->string('profile_picture')->nullable(); // URL or path to the profile picture
            $table->text('notes')->nullable(); // Additional notes about the rider
            $table->string('emergency_contact')->nullable(); // Emergency contact number
            $table->string('emergency_contact_name')->nullable(); // Name of the emergency contact
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_riders');
    }
};
