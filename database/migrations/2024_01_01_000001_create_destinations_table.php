<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('country', 100);
            $table->string('slug', 120)->unique();
            $table->string('image', 255);
            $table->string('tagline', 200)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price_from', 10, 2)->default(0);
            $table->decimal('rating', 2, 1)->default(4.5);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('wiki_title', 150)->nullable();
            $table->string('source', 20)->default('seed');
            $table->timestamp('api_synced_at')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
