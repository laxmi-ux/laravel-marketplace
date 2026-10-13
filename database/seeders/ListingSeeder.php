<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ListingSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Get Seller
        |--------------------------------------------------------------------------
        */

        $seller = Seller::first();

        if (!$seller) {
            $this->command->warn(
                'No seller found. Please run SellerSeeder first.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Helper: Find Category
        |--------------------------------------------------------------------------
        |
        | Your categories table uses "slugs" (plural).
        |
        */

        $getCategory = function (array $slugs) {
            return Category::whereIn('slugs', $slugs)->first();
        };

        /*
        |--------------------------------------------------------------------------
        | Helper: Find Subcategory
        |--------------------------------------------------------------------------
        |
        | Your subcategories table uses "slug" (singular).
        |
        */

        $getSubcategory = function ($categoryId, array $slugs = []) {

            if (!$categoryId) {
                return null;
            }

            $query = Subcategory::where('category_id', $categoryId);

            if (!empty($slugs)) {

                $subcategory = (clone $query)
                    ->whereIn('slug', $slugs)
                    ->first();

                if ($subcategory) {
                    return $subcategory;
                }
            }

            return $query->first();
        };

        /*
        |--------------------------------------------------------------------------
        | Main Categories
        |--------------------------------------------------------------------------
        |
        | Your actual categories are:
        |
        | Properties
        | Land
        | Products
        | Services
        | Projects
        |
        */

        $properties = $getCategory([
            'properties',
            'property',
        ]);

        $land = $getCategory([
            'land',
        ]);

        $products = $getCategory([
            'products',
            'product',
        ]);

        $services = $getCategory([
            'services',
            'service',
        ]);

        $projects = $getCategory([
            'projects',
            'project',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Check Categories
        |--------------------------------------------------------------------------
        */

        if (!$properties) {
            $this->command->warn(
                'Properties category not found.'
            );
        }

        if (!$land) {
            $this->command->warn(
                'Land category not found.'
            );
        }

        if (!$products) {
            $this->command->warn(
                'Products category not found.'
            );
        }

        if (!$services) {
            $this->command->warn(
                'Services category not found.'
            );
        }

        if (!$projects) {
            $this->command->warn(
                'Projects category not found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Listings
        |--------------------------------------------------------------------------
        */

        $listings = [

            /*
            |--------------------------------------------------------------------------
            | 1. Residential Plot
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Residential Plot in Pune',

                'description' =>
                    'Residential plot suitable for house construction in a developing location.',

                'category' => $land,

                'subcategory_slugs' => [
                    'residential-land',
                    'residential-plot',
                    'plot',
                ],

                'listing_type' => 'sale',

                'price' => 8500000,

                'price_unit' => 'total',

                'city' => 'Pune',

                'state' => 'Maharashtra',

                'pincode' => '411001',

                'image' =>
                    'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80',

                'is_featured' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | 2. Commercial Warehouse
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Commercial Warehouse',

                'description' =>
                    'Spacious warehouse suitable for logistics, storage and commercial operations.',

                'category' => $properties,

                'subcategory_slugs' => [
                    'commercial-property',
                    'commercial',
                    'warehouse',
                ],

                'listing_type' => 'lease',

                'price' => 75000,

                'price_unit' => 'per month',

                'city' => 'Pune',

                'state' => 'Maharashtra',

                'pincode' => '411014',

                'image' =>
                    'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80',

                'is_featured' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | 3. Premium 2 BHK Apartment
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Premium 2 BHK Apartment',

                'description' =>
                    'Modern 2 BHK apartment located in a well-connected residential area.',

                'category' => $properties,

                'subcategory_slugs' => [
                    'residential-property',
                    'residential',
                    'apartments',
                    'flat',
                ],

                'listing_type' => 'sale',

                'price' => 6800000,

                'price_unit' => 'total',

                'city' => 'Nashik',

                'state' => 'Maharashtra',

                'pincode' => '422001',

                'image' =>
                    'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1200&q=80',

                'is_featured' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | 4. Industrial Land
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Industrial Land in Nashik',

                'description' =>
                    'Industrial land suitable for manufacturing, warehouse and commercial development.',

                'category' => $land,

                'subcategory_slugs' => [
                    'industrial-land',
                    'industrial',
                    'land',
                ],

                'listing_type' => 'sale',

                'price' => 12500000,

                'price_unit' => 'total',

                'city' => 'Nashik',

                'state' => 'Maharashtra',

                'pincode' => '422010',

                'image' =>
                    'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=1200&q=80',

                'is_featured' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | 5. Premium Cement
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Premium Cement',

                'description' =>
                    'High-quality cement suitable for residential and commercial construction projects.',

                'category' => $products,

                'subcategory_slugs' => [
                    'cement',
                    'construction-materials',
                    'building-materials',
                ],

                'listing_type' => 'sale',

                'price' => 420,

                'price_unit' => 'per bag',

                'city' => 'Nashik',

                'state' => 'Maharashtra',

                'pincode' => '422003',

                'image' =>
                    'https://images.unsplash.com/photo-1518709594023-6eab9bab7b23?auto=format&fit=crop&w=1200&q=80',

                'is_featured' => true,
            ],

            /*
            |--------------------------------------------------------------------------
            | 6. Concrete Paver Blocks
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Concrete Paver Blocks',

                'description' =>
                    'Durable concrete paver blocks suitable for roads, parking areas and landscaping.',

                'category' => $products,

                'subcategory_slugs' => [
                    'paver-blocks',
                    'pavers',
                    'building-materials',
                ],

                'listing_type' => 'sale',

                'price' => 45,

                'price_unit' => 'per piece',

                'city' => 'Nashik',

                'state' => 'Maharashtra',

                'pincode' => '422005',

                'image' =>
                    'https://images.unsplash.com/photo-1590644365607-1c5a5f5b5d3e?auto=format&fit=crop&w=1200&q=80',

                'is_featured' => false,
            ],

            /*
            |--------------------------------------------------------------------------
            | 7. Ready Mix Concrete
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Ready Mix Concrete',

                'description' =>
                    'Transit-mixed ready mix concrete for residential and infrastructure projects.',

                'category' => $products,

                'subcategory_slugs' => [
                    'ready-mix-concrete',
                    'rmc',
                    'concrete',
                ],

                'listing_type' => 'sale',

                'price' => 5500,

                'price_unit' => 'per cubic meter',

                'city' => 'Pune',

                'state' => 'Maharashtra',

                'pincode' => '411038',

                'image' =>
                    'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80',

                'is_featured' => false,
            ],

            /*
            |--------------------------------------------------------------------------
            | 8. Commercial Office Space
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Commercial Office Space',

                'description' =>
                    'Ready-to-use commercial office space suitable for growing businesses.',

                'category' => $properties,

                'subcategory_slugs' => [
                    'commercial-space',
                    'office-space',
                    'commercial',
                ],

                'listing_type' => 'rent',

                'price' => 45000,

                'price_unit' => 'per month',

                'city' => 'Pune',

                'state' => 'Maharashtra',

                'pincode' => '411045',

                'image' =>
                    'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=80',

                'is_featured' => false,
            ],

            /*
            |--------------------------------------------------------------------------
            | 9. Retail Shop
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Retail Shop for Lease',

                'description' =>
                    'Prime retail space available for lease in a high-footfall commercial location.',

                'category' => $properties,

                'subcategory_slugs' => [
                    'retail-space',
                    'shop',
                    'commercial-space',
                ],

                'listing_type' => 'lease',

                'price' => 60000,

                'price_unit' => 'per month',

                'city' => 'Mumbai',

                'state' => 'Maharashtra',

                'pincode' => '400001',

                'image' =>
                    'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?auto=format&fit=crop&w=1200&q=80',

                'is_featured' => false,
            ],

            /*
            |--------------------------------------------------------------------------
            | 10. Residential Land
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Residential Land in Mumbai',

                'description' =>
                    'Residential land parcel suitable for a premium housing development.',

                'category' => $land,

                'subcategory_slugs' => [
                    'residential-land',
                    'residential-plot',
                    'plot',
                ],

                'listing_type' => 'sale',

                'price' => 18500000,

                'price_unit' => 'total',

                'city' => 'Mumbai',

                'state' => 'Maharashtra',

                'pincode' => '400070',

                'image' =>
                    'https://images.unsplash.com/photo-1460317442991-0ec209397118?auto=format&fit=crop&w=1200&q=80',

                'is_featured' => false,
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Insert Listings
        |--------------------------------------------------------------------------
        */

        foreach ($listings as $data) {

            /*
            |--------------------------------------------------------------------------
            | Check Category
            |--------------------------------------------------------------------------
            */

            if (!$data['category']) {

                $this->command->warn(
                    "Category not found for listing: {$data['title']}"
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Find Subcategory
            |--------------------------------------------------------------------------
            */

            $subcategory = $getSubcategory(
                $data['category']->id,
                $data['subcategory_slugs']
            );

            /*
            |--------------------------------------------------------------------------
            | Create / Update Listing
            |--------------------------------------------------------------------------
            */

            Listing::updateOrCreate(
                [
                    'slug' => Str::slug($data['title']),
                ],
                [
                    'title' => $data['title'],

                    'description' => $data['description'],

                    'seller_id' => $seller->id,

                    'category_id' => $data['category']->id,

                    'subcategory_id' => $subcategory?->id,

                    'listing_type' => $data['listing_type'],

                    'price' => $data['price'],

                    'price_unit' => $data['price_unit'],

                    'city' => $data['city'],

                    'state' => $data['state'],

                    'pincode' => $data['pincode'],

                    'seller_name' => $seller->bussiness_name,

                    'is_verified' => (bool) $seller->is_verified,

                    'image' => $data['image'],

                    'status' => 'active',

                    'is_featured' => $data['is_featured'],
                ]
            );
        }

        $this->command->info(
            'Listings seeded successfully.'
        );
    }
}
