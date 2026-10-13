<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Order Reference
            |--------------------------------------------------------------------------
            */
            $table->string('order_number')->unique();

            /*
            |--------------------------------------------------------------------------
            | Main Relationships
            |--------------------------------------------------------------------------
            */
            $table->foreignId('quotation_id')
                ->constrained('quotations')
                ->cascadeOnDelete();

            $table->foreignId('requirement_id')
                ->constrained('requirements')
                ->cascadeOnDelete();

            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('seller_id')
                ->constrained('sellers')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Listing
            |--------------------------------------------------------------------------
            */
            $table->foreignId('listing_id')
                ->nullable()
                ->constrained('listings')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Category
            |--------------------------------------------------------------------------
            */
            $table->string('category', 50);

            /*
            |--------------------------------------------------------------------------
            | Product
            |--------------------------------------------------------------------------
            */
            $table->string('product_name')->nullable();

            $table->decimal('quantity', 15, 2)->nullable();

            $table->string('unit', 100)->nullable();

            $table->decimal('unit_price', 15, 2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Land
            |--------------------------------------------------------------------------
            */
            $table->string('land_location')->nullable();

            $table->decimal('land_area', 15, 2)->nullable();

            $table->string('land_area_unit', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Property
            |--------------------------------------------------------------------------
            */
            $table->string('property_type')->nullable();

            $table->string('property_location')->nullable();

            $table->decimal('property_area', 15, 2)->nullable();

            $table->string('property_area_unit', 100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Service
            |--------------------------------------------------------------------------
            */
            $table->string('service_name')->nullable();

            $table->string('service_duration')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Project
            |--------------------------------------------------------------------------
            */
            $table->string('project_name')->nullable();

            $table->string('project_location')->nullable();

            $table->string('project_timeline')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Pricing Snapshot
            |--------------------------------------------------------------------------
            */
            $table->decimal('subtotal', 15, 2)->default(0);

            $table->decimal('delivery_charges', 15, 2)->default(0);

            $table->decimal('other_charges', 15, 2)->default(0);

            $table->decimal('tax', 15, 2)->default(0);

            $table->decimal('total_amount', 15, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Delivery
            |--------------------------------------------------------------------------
            */
            $table->text('delivery_address')->nullable();

            $table->string('delivery_location')->nullable();

            $table->string('delivery_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */
            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded',
            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Order Status
            |--------------------------------------------------------------------------
            */
            $table->enum('order_status', [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'completed',
                'cancelled',
                'rejected',
            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Order Dates
            |--------------------------------------------------------------------------
            */
            $table->timestamp('ordered_at')->nullable();

            $table->timestamp('confirmed_at')->nullable();

            $table->timestamp('processing_at')->nullable();

            $table->timestamp('shipped_at')->nullable();

            $table->timestamp('delivered_at')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamp('cancelled_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};