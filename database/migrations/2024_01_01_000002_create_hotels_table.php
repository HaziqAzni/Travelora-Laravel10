<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('image', 255);
            $table->tinyInteger('stars')->default(4);
            $table->decimal('rating', 2, 1)->default(4.5);
            $table->integer('reviews')->default(0);
            $table->string('address', 255)->nullable();
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('source', 20)->default('seed');
            $table->string('osm_id', 30)->nullable()->unique();
            // Marks fields that are NOT supplied by the source (e.g. OSM) and are sample estimates
            $table->boolean('price_is_estimate')->default(false);
            $table->string('data_note', 255)->nullable();
            $table->string('amenities', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
