<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@kasse.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '1234567890',
            'active' => true,
        ]);

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@kasse.local',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'phone' => '1234567891',
            'active' => true,
        ]);

        User::create([
            'name' => 'Cashier User',
            'email' => 'cashier@kasse.local',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'phone' => '1234567892',
            'active' => true,
        ]);

        // Create categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices and accessories',
            'active' => true,
        ]);

        $food = Category::create([
            'name' => 'Food & Beverages',
            'slug' => 'food-beverages',
            'description' => 'Food items and drinks',
            'active' => true,
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'slug' => 'clothing',
            'description' => 'Apparel and fashion items',
            'active' => true,
        ]);

        // Create sample products
        Product::create([
            'name' => 'Wireless Mouse',
            'code' => 'ELEC-001',
            'barcode' => '1234567890123',
            'category_id' => $electronics->id,
            'description' => 'Ergonomic wireless mouse',
            'cost' => 15.00,
            'price' => 25.00,
            'stock' => 50,
            'min_stock' => 10,
            'unit' => 'pcs',
            'active' => true,
        ]);

        Product::create([
            'name' => 'USB Cable',
            'code' => 'ELEC-002',
            'barcode' => '1234567890124',
            'category_id' => $electronics->id,
            'description' => 'USB-C charging cable',
            'cost' => 5.00,
            'price' => 12.00,
            'stock' => 100,
            'min_stock' => 20,
            'unit' => 'pcs',
            'active' => true,
        ]);

        Product::create([
            'name' => 'Coffee Beans',
            'code' => 'FOOD-001',
            'barcode' => '2234567890123',
            'category_id' => $food->id,
            'description' => 'Premium Arabica coffee beans',
            'cost' => 8.00,
            'price' => 15.00,
            'stock' => 30,
            'min_stock' => 5,
            'unit' => 'kg',
            'active' => true,
        ]);

        Product::create([
            'name' => 'T-Shirt',
            'code' => 'CLO-001',
            'barcode' => '3234567890123',
            'category_id' => $clothing->id,
            'description' => 'Cotton t-shirt - Medium',
            'cost' => 8.00,
            'price' => 20.00,
            'stock' => 40,
            'min_stock' => 10,
            'unit' => 'pcs',
            'active' => true,
        ]);

        // Create sample customers
        Customer::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '9876543210',
            'address' => '123 Main Street',
            'active' => true,
        ]);

        Customer::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '9876543211',
            'address' => '456 Oak Avenue',
            'active' => true,
        ]);

        // Create sample supplier
        Supplier::create([
            'name' => 'Tech Supplies Co.',
            'company' => 'Tech Supplies Inc.',
            'email' => 'info@techsupplies.com',
            'phone' => '5551234567',
            'address' => '789 Industrial Road',
            'active' => true,
        ]);
    }
}
