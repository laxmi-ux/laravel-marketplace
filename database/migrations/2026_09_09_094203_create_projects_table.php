<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Link to listings
            $table->foreignId('listing_id')
                ->constrained('listings')
                ->cascadeOnDelete();

            // Project-specific information
            $table->string('project_type');
            $table->string('project_status')->nullable();
            $table->string('developer_name')->nullable();

            $table->decimal('total_area', 15, 2)->nullable();
            $table->string('area_unit')->nullable();

            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};