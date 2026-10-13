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
        Schema::create('rfqs', function (Blueprint $table) {

            $table->id();

            // Buyer requirement
            $table->foreignId('requirement_id')
                ->constrained('requirements')
                ->cascadeOnDelete();

            // Seller receiving the RFQ
            $table->foreignId('seller_id')
                ->constrained('sellers')
                ->cascadeOnDelete();

            // RFQ status
            $table->enum('status', [
                'new',
                'viewed',
                'quoted',
                'rejected',
                'expired',
                'closed',
            ])->default('new');

            // RFQ timestamps
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('responded_at')->nullable();

            $table->timestamps();

            // Prevent the same requirement from creating
            // duplicate RFQs for the same seller.
            $table->unique([
                'requirement_id',
                'seller_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfqs');
    }
};