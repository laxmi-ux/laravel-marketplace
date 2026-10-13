<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requirements', function (Blueprint $table) {

            // Product fields
            $table->string('product_name')->nullable()->after('unit');
            $table->string('product_category')->nullable()->after('product_name');
            $table->string('product_quality')->nullable()->after('product_category');
            $table->string('preferred_brand')->nullable()->after('product_quality');
            $table->text('product_specification')->nullable()->after('preferred_brand');

            // Property fields
            $table->string('property_type')->nullable()->after('product_specification');
            $table->string('property_purpose')->nullable()->after('property_type');
            $table->string('property_location')->nullable()->after('property_purpose');
            $table->decimal('property_area', 15, 2)->nullable()->after('property_location');
            $table->string('property_area_unit')->nullable()->after('property_area');
            $table->unsignedInteger('bedrooms')->nullable()->after('property_area_unit');
            $table->unsignedInteger('bathrooms')->nullable()->after('bedrooms');
            $table->text('property_requirements')->nullable()->after('bathrooms');

            // Land fields
            $table->string('land_type')->nullable()->after('property_requirements');
            $table->string('land_purpose')->nullable()->after('land_type');
            $table->string('land_location')->nullable()->after('land_purpose');
            $table->decimal('land_area', 15, 2)->nullable()->after('land_location');
            $table->string('land_area_unit')->nullable()->after('land_area');
            $table->string('road_access')->nullable()->after('land_area_unit');
            $table->text('land_requirements')->nullable()->after('road_access');

            // Service fields
            $table->string('service_name')->nullable()->after('land_requirements');
            $table->string('service_type')->nullable()->after('service_name');
            $table->string('service_location')->nullable()->after('service_type');
            $table->string('service_timeline')->nullable()->after('service_location');
            $table->string('provider_experience')->nullable()->after('service_timeline');
            $table->text('service_scope')->nullable()->after('provider_experience');

            // Project fields
            $table->string('project_type')->nullable()->after('service_scope');
            $table->string('project_name')->nullable()->after('project_type');
            $table->string('project_location')->nullable()->after('project_name');
            $table->decimal('project_area', 15, 2)->nullable()->after('project_location');
            $table->string('project_timeline')->nullable()->after('project_area');
            $table->text('project_scope')->nullable()->after('project_timeline');
        });
    }

    public function down(): void
    {
        Schema::table('requirements', function (Blueprint $table) {
            $table->dropColumn([
                // Product
                'product_name',
                'product_category',
                'product_quality',
                'preferred_brand',
                'product_specification',

                // Property
                'property_type',
                'property_purpose',
                'property_location',
                'property_area',
                'property_area_unit',
                'bedrooms',
                'bathrooms',
                'property_requirements',

                // Land
                'land_type',
                'land_purpose',
                'land_location',
                'land_area',
                'land_area_unit',
                'road_access',
                'land_requirements',

                // Service
                'service_name',
                'service_type',
                'service_location',
                'service_timeline',
                'provider_experience',
                'service_scope',

                // Project
                'project_type',
                'project_name',
                'project_location',
                'project_area',
                'project_timeline',
                'project_scope',
            ]);
        });
    }
};