<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('listing_id')
                ->constrained('listings')
                ->cascadeOnDelete();

            $table->string('document_name');
            $table->string('document_path');
            $table->string('document_type')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_documents');
    }
};