<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Electronics (1-10)
            ['name' => 'Smartphone Pro Max', 'description' => 'Latest flagship smartphone with advanced features', 'price' => 1500000, 'stock' => 25, 'image' => 'smartphone-pro.jpg', 'category' => 'Electronics'],
            ['name' => 'Wireless Headphones', 'description' => 'Premium noise-cancelling wireless headphones', 'price' => 350000, 'stock' => 40, 'image' => 'headphones.jpg', 'category' => 'Electronics'],
            ['name' => 'Laptop Ultra', 'description' => 'Powerful laptop for professionals and creators', 'price' => 2500000, 'stock' => 15, 'image' => 'laptop.jpg', 'category' => 'Electronics'],
            ['name' => 'Smart Watch', 'description' => 'Advanced fitness tracking smart watch', 'price' => 800000, 'stock' => 30, 'image' => 'smartwatch.jpg', 'category' => 'Electronics'],
            ['name' => 'USB-C Cable', 'description' => 'High-speed USB-C charging cable', 'price' => 75000, 'stock' => 100, 'image' => 'usb-cable.jpg', 'category' => 'Electronics'],
            ['name' => 'Power Bank 20000mAh', 'description' => 'Fast charging power bank with LED display', 'price' => 250000, 'stock' => 50, 'image' => 'powerbank.jpg', 'category' => 'Electronics'],
            ['name' => '4K Webcam', 'description' => 'Professional 4K webcam for streaming and meetings', 'price' => 450000, 'stock' => 20, 'image' => 'webcam.jpg', 'category' => 'Electronics'],
            ['name' => 'Mechanical Keyboard', 'description' => 'RGB mechanical keyboard for gaming and typing', 'price' => 600000, 'stock' => 35, 'image' => 'keyboard.jpg', 'category' => 'Electronics'],
            ['name' => 'Gaming Mouse', 'description' => 'High precision gaming mouse with customizable buttons', 'price' => 400000, 'stock' => 45, 'image' => 'mouse.jpg', 'category' => 'Electronics'],
            ['name' => 'Monitor 4K 32inch', 'description' => 'Ultra HD 4K monitor for professional work', 'price' => 3500000, 'stock' => 10, 'image' => 'monitor.jpg', 'category' => 'Electronics'],

            // Fashion (11-20)
            ['name' => 'Cotton T-Shirt', 'description' => 'Comfortable 100% cotton t-shirt in various colors', 'price' => 99000, 'stock' => 80, 'image' => 'tshirt.jpg', 'category' => 'Fashion'],
            ['name' => 'Denim Jeans', 'description' => 'Classic denim jeans with modern fit', 'price' => 350000, 'stock' => 60, 'image' => 'jeans.jpg', 'category' => 'Fashion'],
            ['name' => 'Leather Jacket', 'description' => 'Premium leather jacket for all seasons', 'price' => 1200000, 'stock' => 15, 'image' => 'jacket.jpg', 'category' => 'Fashion'],
            ['name' => 'Sports Shoes', 'description' => 'Comfortable and durable sports running shoes', 'price' => 450000, 'stock' => 50, 'image' => 'shoes.jpg', 'category' => 'Fashion'],
            ['name' => 'Casual Sneakers', 'description' => 'Trendy casual sneakers perfect for everyday wear', 'price' => 350000, 'stock' => 70, 'image' => 'sneakers.jpg', 'category' => 'Fashion'],
            ['name' => 'Winter Coat', 'description' => 'Warm and stylish winter coat with insulation', 'price' => 800000, 'stock' => 25, 'image' => 'coat.jpg', 'category' => 'Fashion'],
            ['name' => 'Summer Dress', 'description' => 'Light and breathable summer dress', 'price' => 250000, 'stock' => 40, 'image' => 'dress.jpg', 'category' => 'Fashion'],
            ['name' => 'Baseball Cap', 'description' => 'Classic baseball cap with adjustable strap', 'price' => 120000, 'stock' => 90, 'image' => 'cap.jpg', 'category' => 'Fashion'],
            ['name' => 'Scarf Wool', 'description' => 'Soft wool scarf perfect for winter', 'price' => 180000, 'stock' => 35, 'image' => 'scarf.jpg', 'category' => 'Fashion'],
            ['name' => 'Leather Wallet', 'description' => 'RFID blocking leather wallet', 'price' => 250000, 'stock' => 55, 'image' => 'wallet.jpg', 'category' => 'Fashion'],

            // Home & Kitchen (21-30)
            ['name' => 'Dinner Set 12pcs', 'description' => 'Beautiful ceramic dinner set for 6 people', 'price' => 450000, 'stock' => 20, 'image' => 'dinnerset.jpg', 'category' => 'Home & Kitchen'],
            ['name' => 'Blender 2000W', 'description' => 'Powerful kitchen blender for smoothies and soups', 'price' => 600000, 'stock' => 30, 'image' => 'blender.jpg', 'category' => 'Home & Kitchen'],
            ['name' => 'Coffee Maker', 'description' => 'Automatic drip coffee maker with timer', 'price' => 350000, 'stock' => 25, 'image' => 'coffeemaker.jpg', 'category' => 'Home & Kitchen'],
            ['name' => 'Knife Set 7pcs', 'description' => 'Professional stainless steel knife set', 'price' => 500000, 'stock' => 18, 'image' => 'knifeset.jpg', 'category' => 'Home & Kitchen'],
            ['name' => 'Rice Cooker', 'description' => 'Multi-function rice cooker with steam tray', 'price' => 400000, 'stock' => 35, 'image' => 'ricecooker.jpg', 'category' => 'Home & Kitchen'],
            ['name' => 'Cookware Set', 'description' => 'Non-stick cookware set with 5 pieces', 'price' => 550000, 'stock' => 22, 'image' => 'cookware.jpg', 'category' => 'Home & Kitchen'],
            ['name' => 'Bed Sheet Set', 'description' => 'Soft cotton bed sheet set 200x200cm', 'price' => 350000, 'stock' => 40, 'image' => 'bedsheet.jpg', 'category' => 'Home & Kitchen'],
            ['name' => 'Pillow Set', 'description' => 'Memory foam pillow set for 2', 'price' => 400000, 'stock' => 30, 'image' => 'pillow.jpg', 'category' => 'Home & Kitchen'],
            ['name' => 'Towel Pack', 'description' => 'Premium cotton towel pack 4 pieces', 'price' => 200000, 'stock' => 60, 'image' => 'towel.jpg', 'category' => 'Home & Kitchen'],
            ['name' => 'Vacuum Cleaner', 'description' => 'Powerful vacuum cleaner with HEPA filter', 'price' => 750000, 'stock' => 12, 'image' => 'vacuum.jpg', 'category' => 'Home & Kitchen'],

            // Sports & Outdoors (31-40)
            ['name' => 'Yoga Mat', 'description' => 'Non-slip yoga mat with carrying strap', 'price' => 150000, 'stock' => 50, 'image' => 'yogamat.jpg', 'category' => 'Sports & Outdoors'],
            ['name' => 'Dumbbells Set', 'description' => 'Adjustable dumbbells set 2-10kg', 'price' => 800000, 'stock' => 20, 'image' => 'dumbbells.jpg', 'category' => 'Sports & Outdoors'],
            ['name' => 'Bicycle', 'description' => 'Mountain bike with 21 speed gears', 'price' => 1200000, 'stock' => 10, 'image' => 'bicycle.jpg', 'category' => 'Sports & Outdoors'],
            ['name' => 'Tennis Racket', 'description' => 'Professional tennis racket with carrying bag', 'price' => 500000, 'stock' => 15, 'image' => 'racket.jpg', 'category' => 'Sports & Outdoors'],
            ['name' => 'Basketball', 'description' => 'Official size basketball for indoor/outdoor', 'price' => 250000, 'stock' => 35, 'image' => 'basketball.jpg', 'category' => 'Sports & Outdoors'],
            ['name' => 'Tent Camping', 'description' => 'Waterproof camping tent for 4 persons', 'price' => 600000, 'stock' => 12, 'image' => 'tent.jpg', 'category' => 'Sports & Outdoors'],
            ['name' => 'Backpack 60L', 'description' => 'Large hiking backpack with rain cover', 'price' => 450000, 'stock' => 25, 'image' => 'backpack.jpg', 'category' => 'Sports & Outdoors'],
            ['name' => 'Fishing Rod', 'description' => 'Professional fishing rod and reel combo', 'price' => 350000, 'stock' => 18, 'image' => 'fishingrod.jpg', 'category' => 'Sports & Outdoors'],
            ['name' => 'Skateboard', 'description' => 'Professional skateboard with grip tape', 'price' => 400000, 'stock' => 22, 'image' => 'skateboard.jpg', 'category' => 'Sports & Outdoors'],
            ['name' => 'Jump Rope', 'description' => 'Speed jump rope with timer counter', 'price' => 120000, 'stock' => 70, 'image' => 'jumprope.jpg', 'category' => 'Sports & Outdoors'],

            // Books & Media (41-50)
            ['name' => 'Programming Book', 'description' => 'Complete guide to web development with PHP', 'price' => 150000, 'stock' => 45, 'image' => 'book1.jpg', 'category' => 'Books & Media'],
            ['name' => 'Novel Adventure', 'description' => 'Epic adventure novel bestseller', 'price' => 80000, 'stock' => 60, 'image' => 'book2.jpg', 'category' => 'Books & Media'],
            ['name' => 'Cookbook', 'description' => '100 easy and delicious recipes for home chefs', 'price' => 120000, 'stock' => 35, 'image' => 'book3.jpg', 'category' => 'Books & Media'],
            ['name' => 'History Book', 'description' => 'World history from ancient to modern times', 'price' => 95000, 'stock' => 30, 'image' => 'book4.jpg', 'category' => 'Books & Media'],
            ['name' => 'Self Help Book', 'description' => 'Guide to personal growth and success', 'price' => 110000, 'stock' => 50, 'image' => 'book5.jpg', 'category' => 'Books & Media'],
            ['name' => 'Art Book', 'description' => 'Contemporary art and photography collection', 'price' => 200000, 'stock' => 15, 'image' => 'book6.jpg', 'category' => 'Books & Media'],
            ['name' => 'Business Book', 'description' => 'Strategies for successful entrepreneurs', 'price' => 130000, 'stock' => 40, 'image' => 'book7.jpg', 'category' => 'Books & Media'],
            ['name' => 'Comics Collection', 'description' => 'Popular comics and manga collection', 'price' => 75000, 'stock' => 55, 'image' => 'book8.jpg', 'category' => 'Books & Media'],
            ['name' => 'Biography', 'description' => 'Inspiring biography of successful people', 'price' => 105000, 'stock' => 25, 'image' => 'book9.jpg', 'category' => 'Books & Media'],
            ['name' => 'Science Book', 'description' => 'Amazing facts about universe and nature', 'price' => 125000, 'stock' => 38, 'image' => 'book10.jpg', 'category' => 'Books & Media'],
        ];

        foreach ($products as $product) {
            $category = DB::table('product_categories')
                ->where('name', $product['category'])
                ->first();

            if ($category) {
                DB::table('products')->insert([
                    'name' => $product['name'],
                    'slug' => Str::slug($product['name']),
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'image' => $product['image'],
                    'product_category_id' => $category->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

