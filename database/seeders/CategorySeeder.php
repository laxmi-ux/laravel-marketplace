<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Properties',
            'slugs' => 'properties',
            'image' => 'https://plus.unsplash.com/premium_photo-1689609950112-d66095626efb?w=500',
            'description' => 'Residential and commercial properties',
            'status' => true,
        ]);

        Category::create([
            'name' => 'Land',
            'slugs' => 'land',
            'image' => 'https://images.unsplash.com/photo-1609154767012-331529e7d73b?w=500',
            'description' => 'Residential, agricultural and commercial land',
            'status' => true,
        ]);

        Category::create([
            'name' => 'Products',
            'slugs' => 'products',
            'image' => 'https://plus.unsplash.com/premium_photo-1682366278673-71e92ce4f503?w=500',
            'description' => 'Construction materials and products',
            'status' => true,
        ]);

        Category::create([
            'name' => 'Services',
            'slugs' => 'services',
            'image' => 'https://images.unsplash.com/photo-1605152276897-4f618f831968?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8c2VydmljZXN8ZW58MHx8MHx8fDA%3D',
            'description' => 'Construction and related services',
            'status' => true,
        ]);

        Category::create([
            'name' => 'Projects',
            'slugs' => 'projects',
            'image' => 'https://plus.unsplash.com/premium_photo-1661290256778-3b821d52c514?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8UHJvamVjdHxlbnwwfHwwfHx8MA%3D%3D',
            'description' => 'Construction projects and requirements',
            'status' => true,
        ]);
    }
}