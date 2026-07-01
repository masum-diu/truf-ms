<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('turfs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('address');
            $table->text('description')->nullable();
            $table->unsignedInteger('price_per_hour');
            $table->string('surface_type')->default('artificial_grass');
            $table->string('size')->nullable();
            $table->json('amenities')->nullable();
            $table->string('image')->nullable();
            $table->time('open_time')->default('06:00:00');
            $table->time('close_time')->default('23:00:00');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turfs');
    }
};
