<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('airline', 100);
            $table->string('from_city', 100);
            $table->string('from_code', 4);
            $table->string('to_city', 100);
            $table->string('to_code', 4);
            $table->time('depart_time');
            $table->time('arrive_time');
            $table->string('duration', 20);
            $table->tinyInteger('stops')->default(0);
            $table->decimal('price', 10, 2);
            $table->integer('seats_left')->default(20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
