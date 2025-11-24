<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
User::updateOrCreate(
    ['email' => 'admin@thriftshop.com'],
    [
        'name' => 'Admin',
        'password' => bcrypt('password'),
        'role' => 'admin',
            'phone' => '081234567890',
    ]
);

// Create Customer
User::updateOrCreate(
    ['email' => 'customer@thriftshop.com'],
    [
        'name' => 'Customer',
        'password' => bcrypt('password'),
        'role' => 'customer',
            'phone' => '081234567890',
    ]
);


        // Create Categories
        $categories = [
            ['name' => 'Pakaian Pria', 'slug' => 'pakaian-pria'],
            ['name' => 'Pakaian Wanita', 'slug' => 'pakaian-wanita'],
            ['name' => 'Sepatu', 'slug' => 'sepatu'],
            ['name' => 'Tas', 'slug' => 'tas'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris'],
            ['name' => 'Elektronik', 'slug' => 'elektronik'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create Products
        $products = [
            [
                'name' => 'Kemeja Flanel Vintage',
                'description' => 'Kemeja flanel vintage kondisi sangat baik, bahan tebal dan nyaman',
                'price' => 85000,
                'condition' => 'good',
                'size' => 'L',
                'brand' => 'Uniqlo',
                'stock' => 1,
                'category_id' => 1,
            ],
            [
                'name' => 'Dress Vintage Flower',
                'description' => 'Dress vintage dengan motif bunga, cocok untuk hangout',
                'price' => 125000,
                'condition' => 'like_new',
                'size' => 'M',
                'brand' => 'Zara',
                'stock' => 1,
                'category_id' => 2,
            ],
            [
                'name' => 'Sneakers Nike Air Max',
                'description' => 'Sneakers Nike Air Max bekas namun masih layak pakai',
                'price' => 350000,
                'condition' => 'good',
                'size' => '42',
                'brand' => 'Nike',
                'stock' => 1,
                'category_id' => 3,
            ],
            [
                'name' => 'Tas Ransel Canvas',
                'description' => 'Tas ransel canvas vintage, banyak kantong',
                'price' => 95000,
                'condition' => 'fair',
                'brand' => 'Local Brand',
                'stock' => 2,
                'category_id' => 4,
            ],
        ];

        foreach ($products as $prod) {
            Product::create([
                'user_id' => 1,
                'category_id' => $prod['category_id'],
                'name' => $prod['name'],
                'slug' => Str::slug($prod['name']) . '-' . Str::random(6),
                'description' => $prod['description'],
                'price' => $prod['price'],
                'stock' => $prod['stock'],
                'condition' => $prod['condition'],
                'size' => $prod['size'] ?? null,
                'brand' => $prod['brand'] ?? null,
                'images' => [],
            ]);
        }
    }
}