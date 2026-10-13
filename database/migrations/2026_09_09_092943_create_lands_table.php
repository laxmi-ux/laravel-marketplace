<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->id();

            // Link to listings table
            $table->foreignId('listing_id')
                ->constrained('listings')
                ->cascadeOnDelete();

            // Land-specific information
            $table->string('land_type');
            $table->decimal('total_area', 15, 2)->nullable();
            $table->string('area_unit')->nullable();

            $table->string('survey_number')->nullable();
            $table->string('gat_number')->nullable();

            $table->string('ownership_type')->nullable();
            $table->string('road_access')->nullable();
            $table->string('zoning')->nullable();

            $table->boolean('water_availability')->default(false);
            $table->boolean('electricity_availability')->default(false);

            $table->text('nearby_infrastructure')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lands');
    }
};