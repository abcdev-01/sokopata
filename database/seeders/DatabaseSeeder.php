<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ==================== CREATE ADMIN ====================
        $admin = User::firstOrCreate(
            ['email' => 'admin@sokopata.co.tz'],
            [
                'name' => 'Admin SokoPata',
                'phone' => '0712345678',
                'password' => Hash::make('password123'),
                'user_type' => 'admin',
                'is_verified' => true,
                'city' => 'Dar es Salaam',
                'rating' => 5.0
            ]
        );

        // ==================== CREATE FARMERS ====================
        $farmers = [
            [
                'name' => 'John Mtambalike',
                'email' => 'john@sokopata.co.tz',
                'phone' => '0712345679',
                'business_name' => 'Green Valley Farms',
                'national_id' => 'TZ123456789',
                'city' => 'Dar es Salaam'
            ],
            [
                'name' => 'Sarah Mushi',
                'email' => 'sarah@sokopata.co.tz',
                'phone' => '0712345680',
                'business_name' => 'Moshi Fresh Produce',
                'national_id' => 'TZ123456790',
                'city' => 'Arusha'
            ],
            [
                'name' => 'James Komba',
                'email' => 'james@sokopata.co.tz',
                'phone' => '0712345681',
                'business_name' => 'Kilimanjaro Organic Farm',
                'national_id' => 'TZ123456791',
                'city' => 'Mwanza'
            ],
            [
                'name' => 'Grace Mrema',
                'email' => 'grace@sokopata.co.tz',
                'phone' => '0712345682',
                'business_name' => 'Mrema Dairy Cooperative',
                'national_id' => 'TZ123456792',
                'city' => 'Dodoma'
            ],
        ];

        $supplierIds = [];
        foreach ($farmers as $farmer) {
            $user = User::firstOrCreate(
                ['email' => $farmer['email']],
                [
                    'name' => $farmer['name'],
                    'phone' => $farmer['phone'],
                    'password' => Hash::make('password123'),
                    'user_type' => 'farmer',
                    'business_name' => $farmer['business_name'],
                    'business_registration_number' => 'REG' . rand(10000, 99999),
                    'national_id' => $farmer['national_id'],
                    'address' => $farmer['business_name'] . ', ' . $farmer['city'],
                    'city' => $farmer['city'],
                    'is_verified' => true,
                    'rating' => 4.5
                ]
            );
            $supplierIds[] = $user->id;
        }

        // ==================== CREATE COOPERATIVE ====================
        $cooperative = User::firstOrCreate(
            ['email' => 'coop@sokopata.co.tz'],
            [
                'name' => 'Northern Zone Farmers Cooperative',
                'phone' => '0712345683',
                'password' => Hash::make('password123'),
                'user_type' => 'cooperative',
                'business_name' => 'Northern Farmers Cooperative Society',
                'business_registration_number' => 'COOP2024001',
                'national_id' => 'TZ123456793',
                'address' => 'Arusha Road, Moshi',
                'city' => 'Arusha',
                'is_verified' => true,
                'rating' => 4.8
            ]
        );
        $supplierIds[] = $cooperative->id;

        // ==================== CREATE BUYERS ====================
        $buyers = [
            [
                'name' => 'Sarah Hotel Manager',
                'email' => 'sarah.hotel@sokopata.co.tz',
                'phone' => '0712345684',
                'business_name' => 'Serena Hotel Dar es Salaam',
                'city' => 'Dar es Salaam'
            ],
            [
                'name' => 'Michael Restaurant Owner',
                'email' => 'michael@sokopata.co.tz',
                'phone' => '0712345685',
                'business_name' => 'Taste of Tanzania Restaurant',
                'city' => 'Dar es Salaam'
            ],
            [
                'name' => 'Elizabeth Catering',
                'email' => 'elizabeth@sokopata.co.tz',
                'phone' => '0712345686',
                'business_name' => 'Royal Catering Services',
                'city' => 'Arusha'
            ],
            [
                'name' => 'Peter School Manager',
                'email' => 'peter@sokopata.co.tz',
                'phone' => '0712345687',
                'business_name' => 'International School of Dar es Salaam',
                'city' => 'Dar es Salaam'
            ],
            [
                'name' => 'John Resort Owner',
                'email' => 'john.resort@sokopata.co.tz',
                'phone' => '0712345688',
                'business_name' => 'Zanzibar Beach Resort',
                'city' => 'Dar es Salaam'
            ],
        ];

        $buyerIds = [];
        foreach ($buyers as $buyer) {
            $user = User::firstOrCreate(
                ['email' => $buyer['email']],
                [
                    'name' => $buyer['name'],
                    'phone' => $buyer['phone'],
                    'password' => Hash::make('password123'),
                    'user_type' => 'buyer',
                    'business_name' => $buyer['business_name'],
                    'address' => $buyer['business_name'] . ', ' . $buyer['city'],
                    'city' => $buyer['city'],
                    'is_verified' => true,
                    'rating' => 4.2
                ]
            );
            $buyerIds[] = $user->id;
        }

        // ==================== CREATE PRODUCTS ====================
        $products = [
            // Green Valley Farms (John) - Vegetables
            ['supplier_id' => $supplierIds[0], 'name' => 'Fresh Tomatoes', 'category' => 'vegetables', 'description' => 'Ripe, fresh tomatoes from our farm. Perfect for sauces and salads.', 'price' => 1500, 'unit' => 'kg', 'quantity' => 500, 'location' => 'Dar es Salaam', 'min_order_quantity' => 5],
            ['supplier_id' => $supplierIds[0], 'name' => 'Green Spinach', 'category' => 'vegetables', 'description' => 'Fresh organic spinach, rich in iron and vitamins.', 'price' => 2000, 'unit' => 'bunch', 'quantity' => 300, 'location' => 'Dar es Salaam', 'min_order_quantity' => 10],
            ['supplier_id' => $supplierIds[0], 'name' => 'Red Onions', 'category' => 'vegetables', 'description' => 'Quality red onions, great for cooking.', 'price' => 1200, 'unit' => 'kg', 'quantity' => 1000, 'location' => 'Dar es Salaam', 'min_order_quantity' => 10],
            
            // Moshi Fresh Produce (Sarah) - Fruits
            ['supplier_id' => $supplierIds[1], 'name' => 'Sweet Bananas', 'category' => 'fruits', 'description' => 'Sweet, ripe bananas from Moshi.', 'price' => 1000, 'unit' => 'bunch', 'quantity' => 200, 'location' => 'Arusha', 'min_order_quantity' => 5],
            ['supplier_id' => $supplierIds[1], 'name' => 'Fresh Oranges', 'category' => 'fruits', 'description' => 'Juicy oranges, great for juice.', 'price' => 1800, 'unit' => 'kg', 'quantity' => 400, 'location' => 'Arusha', 'min_order_quantity' => 10],
            ['supplier_id' => $supplierIds[1], 'name' => 'Ripe Mangoes', 'category' => 'fruits', 'description' => 'Sweet, juicy mangoes - in season!', 'price' => 3000, 'unit' => 'kg', 'quantity' => 150, 'location' => 'Arusha', 'min_order_quantity' => 5],
            
            // Kilimanjaro Organic Farm (James) - Vegetables & Fruits
            ['supplier_id' => $supplierIds[2], 'name' => 'Organic Carrots', 'category' => 'vegetables', 'description' => 'Fresh organic carrots, rich in beta-carotene.', 'price' => 2000, 'unit' => 'kg', 'quantity' => 300, 'location' => 'Mwanza', 'min_order_quantity' => 5],
            ['supplier_id' => $supplierIds[2], 'name' => 'Cabbage', 'category' => 'vegetables', 'description' => 'Large, fresh cabbages.', 'price' => 1500, 'unit' => 'piece', 'quantity' => 200, 'location' => 'Mwanza', 'min_order_quantity' => 10],
            ['supplier_id' => $supplierIds[2], 'name' => 'Avocados', 'category' => 'fruits', 'description' => 'Creamy avocados, perfect for guacamole.', 'price' => 1000, 'unit' => 'piece', 'quantity' => 500, 'location' => 'Mwanza', 'min_order_quantity' => 20],
            
            // Mrema Dairy Cooperative (Grace) - Dairy
            ['supplier_id' => $supplierIds[3], 'name' => 'Fresh Milk', 'category' => 'dairy', 'description' => 'Pasteurized fresh milk, 1 liter packets.', 'price' => 2000, 'unit' => 'liter', 'quantity' => 500, 'location' => 'Dodoma', 'min_order_quantity' => 20],
            ['supplier_id' => $supplierIds[3], 'name' => 'Yogurt', 'category' => 'dairy', 'description' => 'Creamy yogurt, 500ml cups.', 'price' => 1500, 'unit' => 'cup', 'quantity' => 300, 'location' => 'Dodoma', 'min_order_quantity' => 20],
            ['supplier_id' => $supplierIds[3], 'name' => 'Cheese', 'category' => 'dairy', 'description' => 'Fresh cheese, 250g blocks.', 'price' => 5000, 'unit' => 'piece', 'quantity' => 100, 'location' => 'Dodoma', 'min_order_quantity' => 10],
            
            // Northern Farmers Cooperative - Grains
            ['supplier_id' => $supplierIds[4], 'name' => 'Maize Flour', 'category' => 'grains', 'description' => 'Fine maize flour, 2kg packets.', 'price' => 3500, 'unit' => 'kg', 'quantity' => 1000, 'location' => 'Arusha', 'min_order_quantity' => 50],
            ['supplier_id' => $supplierIds[4], 'name' => 'Rice (Premium)', 'category' => 'grains', 'description' => 'High-quality Tanzanian rice.', 'price' => 4000, 'unit' => 'kg', 'quantity' => 800, 'location' => 'Arusha', 'min_order_quantity' => 50],
            ['supplier_id' => $supplierIds[4], 'name' => 'Beans', 'category' => 'grains', 'description' => 'Dried beans, protein-rich.', 'price' => 3000, 'unit' => 'kg', 'quantity' => 600, 'location' => 'Arusha', 'min_order_quantity' => 25],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                [
                    'supplier_id' => $product['supplier_id'],
                    'name' => $product['name']
                ],
                $product
            );
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('📝 Test Accounts:');
        $this->command->info('');
        $this->command->info('🔵 ADMIN:');
        $this->command->info('   Email: admin@sokopata.co.tz');
        $this->command->info('   Password: password123');
        $this->command->info('');
        $this->command->info('🟢 FARMERS/SUPPLIERS:');
        $this->command->info('   Email: john@sokopata.co.tz | Password: password123');
        $this->command->info('   Email: sarah@sokopata.co.tz | Password: password123');
        $this->command->info('   Email: james@sokopata.co.tz | Password: password123');
        $this->command->info('   Email: grace@sokopata.co.tz | Password: password123');
        $this->command->info('   Email: coop@sokopata.co.tz | Password: password123');
        $this->command->info('');
        $this->command->info('🟡 BUYERS (Restaurants/Hotels):');
        $this->command->info('   Email: sarah.hotel@sokopata.co.tz | Password: password123');
        $this->command->info('   Email: michael@sokopata.co.tz | Password: password123');
        $this->command->info('   Email: elizabeth@sokopata.co.tz | Password: password123');
        $this->command->info('   Email: peter@sokopata.co.tz | Password: password123');
        $this->command->info('   Email: john.resort@sokopata.co.tz | Password: password123');
    }
}