<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {

            $table->id();

            // Buyer enquiry / requirement
            $table->foreignId('requirement_id')
                ->constrained('requirements')
                ->cascadeOnDelete();

            // Seller who is sending quotation
            $table->foreignId('seller_id')
                ->constrained('sellers')
                ->cascadeOnDelete();

            // Buyer receiving quotation
            $table->foreignId('buyer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Quotation details
            $table->decimal('quantity', 15, 2);
            $table->string('unit', 100);

            // Pricing
            $table->decimal('unit_price', 15, 2);
            $table->decimal('delivery_charges', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);

            // Quotation validity
            $table->date('valid_until');

            // Delivery information
            $table->string('delivery_time', 100)->nullable();

            // Additional seller notes
            $table->text('notes')->nullable();

            // Quotation status
            $table->enum('status', [
                'sent',
                'accepted',
                'rejected',
                'countered',
                'expired'
            ])->default('sent');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};