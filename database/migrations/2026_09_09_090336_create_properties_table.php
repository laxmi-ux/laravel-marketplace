<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            // Link to common listing
            $table->foreignId('listing_id')
                ->constrained('listings')
                ->cascadeOnDelete();

            // Property-specific information
            $table->string('property_type');
            $table->decimal('area', 15, 2)->nullable();
            $table->string('area_unit')->nullable();

            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();

            $table->json('amenities')->nullable();

            $table->string('property_status')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};