<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('things_to_do', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->string('title', 150);
            $table->string('image', 255);
            $table->string('category', 60);
            $table->string('duration', 40);
            $table->decimal('price', 10, 2);
            $table->decimal('rating', 2, 1)->default(4.5);
            $table->integer('reviews')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('things_to_do');
    }
};
