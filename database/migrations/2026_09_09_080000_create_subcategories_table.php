<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();

            // Main category
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            // Subcategory name
            $table->string('name');

            // Clean URL
            $table->string('slug');

            // Optional image and description
            $table->string('image')->nullable();
            $table->text('description')->nullable();

            // Active / inactive
            $table->boolean('status')->default(true);

            $table->timestamps();

            // Same subcategory name can exist under different categories
            $table->unique(['category_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};