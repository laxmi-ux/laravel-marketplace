<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            // Link to listings
            $table->foreignId('listing_id')
                ->constrained('listings')
                ->cascadeOnDelete();

            // Service-specific information
            $table->string('service_type');
            $table->string('service_area')->nullable();
            $table->string('experience')->nullable();
            $table->string('availability')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};