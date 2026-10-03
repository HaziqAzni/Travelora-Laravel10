<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('booking_type', ['hotel', 'flight', 'package', 'activity']);
            $table->unsignedBigInteger('item_id');
            $table->string('item_name', 150);
            $table->date('travel_date');
            $table->date('end_date')->nullable();
            $table->integer('guests')->default(1);
            $table->decimal('total_price', 10, 2);
            // Demo requests default to pending: there is no live availability or payment check.
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->boolean('is_demo')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
