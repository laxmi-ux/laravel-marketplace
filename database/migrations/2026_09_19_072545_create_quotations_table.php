<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */
            $table->foreignId('requirement_id')
                ->constrained('requirements')
                ->cascadeOnDelete();

            $table->foreignId('seller_id')
                ->constrained('sellers')
                ->cascadeOnDelete();

            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('category', 50);


            /*
            |--------------------------------------------------------------------------
            | Product Quotation
            |--------------------------------------------------------------------------
            */
            $table->string('product_name')->nullable();

            $table->decimal('quantity', 15, 2)->nullable();

            $table->string('unit', 100)->nullable();

            $table->decimal('unit_price', 15, 2)->nullable();

            $table->decimal('product_subtotal', 15, 2)->nullable();


            /*
            |--------------------------------------------------------------------------
            | Land Quotation
            |--------------------------------------------------------------------------
            */
            $table->string('land_location')->nullable();

            $table->decimal('land_area', 15, 2)->nullable();

            $table->string('land_area_unit', 100)->nullable();

            $table->decimal('land_price_per_unit', 15, 2)->nullable();

            $table->decimal('land_price', 15, 2)->nullable();

            $table->decimal('development_charges', 15, 2)
                ->default(0);

            $table->decimal('registration_charges', 15, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Property Quotation
            |--------------------------------------------------------------------------
            */
            $table->string('property_type')->nullable();

            $table->string('property_location')->nullable();

            $table->decimal('property_area', 15, 2)->nullable();

            $table->string('property_area_unit', 100)->nullable();

            $table->decimal('property_price', 15, 2)->nullable();

            $table->decimal('maintenance_charges', 15, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Service Quotation
            |--------------------------------------------------------------------------
            */
            $table->string('service_name')->nullable();

            $table->text('service_description')->nullable();

            $table->string('service_duration')->nullable();

            $table->decimal('service_charges', 15, 2)
                ->default(0);

            $table->decimal('labour_charges', 15, 2)
                ->default(0);

            $table->decimal('material_charges', 15, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Project Quotation
            |--------------------------------------------------------------------------
            */
            $table->string('project_name')->nullable();

            $table->string('project_location')->nullable();

            $table->text('project_scope')->nullable();

            $table->decimal('project_value', 15, 2)->nullable();

            $table->decimal('professional_charges', 15, 2)
                ->default(0);

            $table->string('project_timeline')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Common Charges
            |--------------------------------------------------------------------------
            */
            $table->decimal('delivery_charges', 15, 2)
                ->default(0);

            $table->decimal('other_charges', 15, 2)
                ->default(0);

            $table->decimal('tax', 15, 2)
                ->default(0);

            $table->decimal('total_amount', 15, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | Quotation Terms
            |--------------------------------------------------------------------------
            */
            $table->string('delivery_time')->nullable();

            $table->text('warranty')->nullable();

            $table->date('valid_until');

            $table->text('notes')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'sent',
                'accepted',
                'rejected',
                'countered',
                'expired',
            ])->default('sent');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};