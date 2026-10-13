<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bids', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('listing_id')
                ->constrained('listings')
                ->cascadeOnDelete();

            $table->foreignId('seller_id')
                ->constrained('sellers')
                ->cascadeOnDelete();

            $table->decimal('bid_amount', 15, 2);

            $table->text('message')->nullable();

            $table->string('status')->default('pending');

            $table->text('seller_response')->nullable();

            $table->timestamp('responded_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};