<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $properties = Category::where('slugs', 'properties')->first();

        if ($properties) {
            foreach ([
                'Residential',
                'Commercial',
                'Industrial',
                'Agricultural',
                'Rental',
                'For Sale',
                'For Lease',
                'New Projects',
                'Under-Construction Projects',
            ] as $name) {
                Subcategory::create([
                    'category_id' => $properties->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $name . ' properties',
                    'status' => true,
                ]);
            }
        }

        $land = Category::where('slugs', 'land')->first();

        if ($land) {
            foreach ([
                'Residential Development',
                'Commercial Development',
                'Industrial Development',
                'Agriculture',
                'Warehousing',
                'Solar Projects',
                'Infrastructure Projects',
                'Investment',
            ] as $name) {
                Subcategory::create([
                    'category_id' => $land->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $name . ' land',
                    'status' => true,
                ]);
            }
        }

        $products = Category::where('slugs', 'products')->first();

        if ($products) {
            foreach ([
                'Building Materials',
                'Construction Equipment',
            ] as $name) {
                Subcategory::create([
                    'category_id' => $products->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $name,
                    'status' => true,
                ]);
            }
        }

        $services = Category::where('slugs', 'services')->first();

        if ($services) {
            foreach ([
                'Civil Contractors',
                'Electrical Contractors',
                'Plumbing Contractors',
                'HVAC Contractors',
                'Structural Contractors',
                'Architects',
                'Interior Designers',
                'Surveyors',
                'Quantity Surveyors',
                'Project Management Consultants',
                'Soil Testing Agencies',
                'Geotechnical Consultants',
                'Legal / Property Consultants',
                'Valuers',
                'Safety Consultants',
                'Environmental Consultants',
            ] as $name) {
                Subcategory::create([
                    'category_id' => $services->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $name,
                    'status' => true,
                ]);
            }
        }
    }
}