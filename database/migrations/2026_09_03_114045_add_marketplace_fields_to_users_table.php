<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['buyer', 'seller'])->default('buyer');
            }

            if (!Schema::hasColumn('users', 'business_name')) {
                $table->string('business_name')->nullable();
            }

            if (!Schema::hasColumn('users', 'business_category')) {
                $table->string('business_category')->nullable();
            }

            if (!Schema::hasColumn('users', 'gst_number')) {
                $table->string('gst_number')->nullable();
            }

            if (!Schema::hasColumn('users', 'business_address')) {
                $table->text('business_address')->nullable();
            }

            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable();
            }

            if (!Schema::hasColumn('users', 'pincode')) {
                $table->string('pincode')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $columns = [
                'phone',
                'role',
                'business_name',
                'business_category',
                'gst_number',
                'business_address',
                'city',
                'pincode',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};