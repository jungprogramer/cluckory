<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Signature Crispy Chicken',
                'description' => 'Our world-famous golden-brown chicken, marinated for 24 hours.',
                'price' => 45000,
                'image' => 'products/signature-chicken.jpg',
                'is_bestseller' => true,
                'is_best_value' => false,
                'is_active' => true,
            ],
            [
                'name' => 'The Firecracker Burger',
                'description' => 'Spicy chicken burger with jalapeños and ghost pepper sauce.',
                'price' => 55000,
                'image' => 'products/firecracker-burger.jpg',
                'is_bestseller' => false,
                'is_best_value' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Cluckory Waffle Fries',
                'description' => 'Crispy waffle fries with Cajun seasoning.',
                'price' => 25000,
                'image' => 'products/waffle-fries.jpg',
                'is_bestseller' => false,
                'is_best_value' => false,
                'is_active' => true,
            ],
            [
                'name' => 'The Ultimate Bucket',
                'description' => '12 pieces chicken, 4 sides, and 2 drinks for the whole squad.',
                'price' => 199000,
                'image' => 'products/ultimate-bucket.jpg',
                'is_bestseller' => false,
                'is_best_value' => true,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            $name = $product['name'];
            unset($product['name']);
            Product::updateOrCreate(
                ['name' => $name],
                $product
            );
        }
    }
}