<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Buyer
            |--------------------------------------------------------------------------
            */
            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Seller
            |--------------------------------------------------------------------------
            */
            $table->foreignId('seller_id')
                ->constrained('sellers')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Listing
            |--------------------------------------------------------------------------
            | Nullable because some orders may be for services,
            | properties, land, projects, etc.
            |--------------------------------------------------------------------------
            */
            $table->foreignId('listing_id')
                ->nullable()
                ->constrained('listings')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Order
            |--------------------------------------------------------------------------
            | Review belongs to a specific completed order.
            |--------------------------------------------------------------------------
            */
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Rating
            |--------------------------------------------------------------------------
            */
            $table->unsignedTinyInteger('rating');

            /*
            |--------------------------------------------------------------------------
            | Review
            |--------------------------------------------------------------------------
            */
            $table->text('review')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Moderation Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Prevent duplicate review for same order by same buyer
            |--------------------------------------------------------------------------
            */
            $table->unique(
                ['order_id', 'buyer_id'],
                'reviews_order_buyer_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};