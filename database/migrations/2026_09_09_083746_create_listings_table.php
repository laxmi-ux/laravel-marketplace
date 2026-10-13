<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();

            // Seller
            $table->foreignId('seller_id')
                ->nullable()
                ->constrained('sellers')
                ->nullOnDelete();

            // Basic information
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Category
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            // Subcategory
            $table->foreignId('subcategory_id')
                ->nullable()
                ->constrained('subcategories')
                ->nullOnDelete();

            // Listing type
            $table->enum('listing_type', [
                'sale',
                'rent',
                'lease',
                'buy'
            ])->default('sale');

            // Price
            $table->decimal('price', 15, 2)->nullable();
            $table->string('price_unit')->nullable();

            // Location
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();

            // Seller information
            $table->string('seller_name')->nullable();
            $table->boolean('is_verified')->default(false);

            // Main image
            $table->string('image')->nullable();

            // Status
            $table->enum('status', [
                'active',
                'inactive',
                'sold'
            ])->default('active');

            // Featured
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};