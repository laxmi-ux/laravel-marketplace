<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {

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

            $table->string('name');

            $table->string('email');

            $table->string('phone', 20)->nullable();

            $table->unsignedInteger('quantity')->nullable();

            $table->string('unit')->nullable();

            $table->text('message')->nullable();

            $table->text('seller_response')->nullable();

            $table->string('status')
                ->default('new');

            $table->timestamp('responded_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};