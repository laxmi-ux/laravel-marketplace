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
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('listing_id')
                ->nullable()
                ->constrained('listings')
                ->nullOnDelete();

            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();

            $table->integer('quantity')->nullable();
            $table->string('unit')->nullable();

            $table->text('message')->nullable();

            $table->enum('status', [
                'pending_admin',
                'new',
                'contacted',
                'quotation_sent',
                'closed'
            ])->default('new');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirements');
    }
};
