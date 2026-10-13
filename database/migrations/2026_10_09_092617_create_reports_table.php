<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reporter_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('reported_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('listing_id')
                ->nullable()
                ->constrained('listings')
                ->nullOnDelete();

            $table->string('subject');
            $table->string('reason');
            $table->text('description');
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};