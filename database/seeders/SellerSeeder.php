<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Seller;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seller::updateOrCreate(
            ['slug' => 'elr-industries'],
            [
                'bussiness_name' => 'ELR Industries',
                'category' => 'Building Materials',
                'location' => 'Nashik, Maharashtra',
                'links'=>'',
                'logo' => 'uploads/ELR.png',
                'is_verified' => true,
                'is_featured' => true,
            ]
        );

        Seller::updateOrCreate(
            ['slug' => 'abc-cement'],
            [
                'bussiness_name' => 'ABC Cement',
                'category' => 'Cement & Construction',
                'location' => 'Pune, Maharashtra',
                'links'=>'',
                'logo' => 'https://images.unsplash.com/photo-1649734926671-9a46a4d519b5?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YWJjJTIwbG9nb3xlbnwwfHwwfHx8MA%3D%3D',
                'is_verified' => true,
                'is_featured' => true,
            ]
        );

        Seller::updateOrCreate(
            ['slug' => 'xyz-steel'],
            [
                'bussiness_name' => 'XYZ Steel',
                'category' => 'Steel & TMT Products',
                'location' => 'Mumbai, Maharashtra',
                'links'=>'',
                'logo' => 'uploads/XYZ.png',
                'is_verified' => true,
                'is_featured' => true,
            ]
        );

        Seller::updateOrCreate(
            ['slug' => 'prime-properties'],
            [
                'bussiness_name' => 'Prime Properties',
                'category' => 'Properties & Land',
                'location' => 'Pune, Maharashtra',
                'links' => '',
                'logo' => 'https://media.istockphoto.com/id/628409792/photo/golden-house-real-estate-3d-render-illustration.webp?a=1&b=1&s=612x612&w=0&k=20&c=QGw2hSRpGMhVhK_jwK5Ta68Nmoo2pBp6JjKRx7b8xN8=',
                'is_verified' => true,
                'is_featured' => true,
            ]
        );
    }
}
