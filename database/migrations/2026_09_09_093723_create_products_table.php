<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Link to listings table
            $table->foreignId('listing_id')
                ->constrained('listings')
                ->cascadeOnDelete();

            // Product-specific information
            $table->string('brand')->nullable();
            $table->string('product_code')->nullable();

            $table->decimal('quantity', 15, 2)->nullable();
            $table->string('unit')->nullable();

            $table->json('specifications')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};