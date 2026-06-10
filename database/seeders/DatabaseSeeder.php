<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name'     => 'Admin DIB',
            'email'    => 'admin@dibproductions.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Test customer
        User::create([
            'name'     => 'Test Customer',
            'email'    => 'customer@test.com',
            'password' => Hash::make('password'),
            'role'     => 'customer',
        ]);

        // Categories
        $categories = [
            ['name' => 'Electronics',  'slug' => 'electronics',  'description' => 'Cutting-edge electronics and gadgets.'],
            ['name' => 'Clothing',     'slug' => 'clothing',     'description' => 'Premium fashion and apparel.'],
            ['name' => 'Audio',        'slug' => 'audio',        'description' => 'Professional-grade audio equipment.'],
            ['name' => 'Accessories',  'slug' => 'accessories',  'description' => 'Lifestyle and tech accessories.'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Products
        $products = [
            ['name' => 'DIB Pro Headphones X1',  'category' => 'audio',       'price' => 249.99, 'sale_price' => 199.99, 'stock' => 50,  'featured' => true],
            ['name' => 'DIB Smart Watch Ultra',   'category' => 'electronics', 'price' => 399.99, 'sale_price' => null,   'stock' => 30,  'featured' => true],
            ['name' => 'DIB Wireless Earbuds',    'category' => 'audio',       'price' => 129.99, 'sale_price' => 99.99,  'stock' => 100, 'featured' => false],
            ['name' => 'DIB Studio Hoodie',       'category' => 'clothing',    'price' => 89.99,  'sale_price' => null,   'stock' => 75,  'featured' => true],
            ['name' => 'DIB Mechanical Keyboard', 'category' => 'electronics', 'price' => 179.99, 'sale_price' => 149.99, 'stock' => 45,  'featured' => false],
            ['name' => 'DIB Laptop Stand Pro',    'category' => 'accessories', 'price' => 59.99,  'sale_price' => null,   'stock' => 200, 'featured' => false],
            ['name' => 'DIB 4K Webcam',           'category' => 'electronics', 'price' => 219.99, 'sale_price' => 189.99, 'stock' => 60,  'featured' => true],
            ['name' => 'DIB Logo Cap',            'category' => 'clothing',    'price' => 34.99,  'sale_price' => null,   'stock' => 150, 'featured' => false],
            ['name' => 'DIB USB-C Hub 7-in-1',   'category' => 'accessories', 'price' => 79.99,  'sale_price' => 64.99,  'stock' => 80,  'featured' => false],
            ['name' => 'DIB Speaker S200',        'category' => 'audio',       'price' => 299.99, 'sale_price' => null,   'stock' => 25,  'featured' => true],
        ];

        foreach ($products as $p) {
            $category = Category::where('slug', $p['category'])->first();
            Product::create([
                'name'        => $p['name'],
                'slug'        => Str::slug($p['name']),
                'description' => "Premium quality {$p['name']} from DIB Productions. Designed for performance and style, this product embodies the DIB commitment to excellence.",
                'price'       => $p['price'],
                'sale_price'  => $p['sale_price'],
                'stock'       => $p['stock'],
                'category_id' => $category->id,
                'is_featured' => $p['featured'],
                'is_active'   => true,
            ]);
        }
    }
}
