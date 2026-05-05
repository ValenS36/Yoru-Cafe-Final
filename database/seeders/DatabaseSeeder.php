<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        User::create([
            'name' => 'Admin Cafe',
            'email' => 'admin@yorucafe.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Cashier 1',
            'email' => 'cashier@yorucafe.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
        ]);

        // 2. Seed Categories
        $categories = [
            ['name' => 'Makanan', 'slug' => 'makanan'],
            ['name' => 'Minuman', 'slug' => 'minuman'],
            ['name' => 'Snack', 'slug' => 'snack'],
            ['name' => 'Paket', 'slug' => 'paket'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // 3. Seed Menus
        $menus = [
            // Makanan (Category ID 1)
            ['category_id' => 1, 'name' => 'Yoru Burger', 'slug' => Str::slug('Yoru Burger'), 'price' => 45000, 'description' => 'Burger signature dengan beef patty spesial', 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&q=80', 'is_available' => true],
            ['category_id' => 1, 'name' => 'Beef Bowl', 'slug' => Str::slug('Beef Bowl'), 'price' => 38000, 'description' => 'Nasi dengan irisan daging sapi manis gurih', 'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=500&q=80', 'is_available' => true],
            ['category_id' => 1, 'name' => 'Spicy Ramen', 'slug' => Str::slug('Spicy Ramen'), 'price' => 42000, 'description' => 'Ramen pedas dengan topping lengkap', 'image' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=500&q=80', 'is_available' => true],
            ['category_id' => 1, 'name' => 'Chicken Katsu', 'slug' => Str::slug('Chicken Katsu'), 'price' => 35000, 'description' => 'Ayam katsu renyah dengan salad', 'image' => 'https://images.unsplash.com/photo-1598514982205-f36b96d1e8d4?w=500&q=80', 'is_available' => true],
            
            // Minuman (Category ID 2)
            ['category_id' => 2, 'name' => 'Iced Yoru Latte', 'slug' => Str::slug('Iced Yoru Latte'), 'price' => 28000, 'description' => 'Kopi susu gula aren khas YoruCafe', 'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=500&q=80', 'is_available' => true],
            ['category_id' => 2, 'name' => 'Matcha Frappe', 'slug' => Str::slug('Matcha Frappe'), 'price' => 32000, 'description' => 'Frappe matcha segar dengan whipped cream', 'image' => 'https://images.unsplash.com/photo-1515823064-d6e0c04616a7?w=500&q=80', 'is_available' => true],
            ['category_id' => 2, 'name' => 'Lemon Tea', 'slug' => Str::slug('Lemon Tea'), 'price' => 18000, 'description' => 'Es teh lemon segar', 'image' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=500&q=80', 'is_available' => true],
            ['category_id' => 2, 'name' => 'Americano', 'slug' => Str::slug('Americano'), 'price' => 20000, 'description' => 'Kopi hitam tanpa gula', 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=500&q=80', 'is_available' => true],

            // Snack (Category ID 3)
            ['category_id' => 3, 'name' => 'French Fries', 'slug' => Str::slug('French Fries'), 'price' => 20000, 'description' => 'Kentang goreng renyah', 'image' => 'https://images.unsplash.com/photo-1576107232684-1279f390859f?w=500&q=80', 'is_available' => true],
            ['category_id' => 3, 'name' => 'Spicy Wings', 'slug' => Str::slug('Spicy Wings'), 'price' => 28000, 'description' => 'Sayap ayam bumbu pedas manis', 'image' => 'https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=500&q=80', 'is_available' => true],
            ['category_id' => 3, 'name' => 'Onion Rings', 'slug' => Str::slug('Onion Rings'), 'price' => 22000, 'description' => 'Bawang bombay goreng tepung', 'image' => 'https://images.unsplash.com/photo-1639024471283-03518883512d?w=500&q=80', 'is_available' => true],

            // Paket (Category ID 4)
            ['category_id' => 4, 'name' => 'Paket Kenyang 1', 'slug' => Str::slug('Paket Kenyang 1'), 'price' => 55000, 'description' => 'Yoru Burger + French Fries + Lemon Tea', 'image' => 'https://images.unsplash.com/photo-1594212691516-7463f1db894d?w=500&q=80', 'is_available' => true],
            ['category_id' => 4, 'name' => 'Paket Kenyang 2', 'slug' => Str::slug('Paket Kenyang 2'), 'price' => 50000, 'description' => 'Chicken Katsu + Iced Yoru Latte', 'image' => 'https://images.unsplash.com/photo-1588677443195-2bd33e461b47?w=500&q=80', 'is_available' => true],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
