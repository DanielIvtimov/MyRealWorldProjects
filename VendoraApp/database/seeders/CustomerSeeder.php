<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test customer
        $testCustomer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create default address for the test customer
        Address::factory()->default()->create([
            'customer_id' => $testCustomer->id,
        ]);

        // Create additional address 
        Address::factory()->create([
            'customer_id' => $testCustomer->id,
        ]);

        // Create 50 more customers
        $this->command->info('Creating customers...');
        $bar = $this->command->getOutput()->createProgressBar(50);
        $bar->start();

        for ($i = 0; $i < 50; $i++) {
            $customer = Customer::factory()->create();

            // Create 1-3 addresses per customer
            Address::factory()->default()->create([
                'customer_id' => $customer->id,
            ]);

            if (rand(0, 100) > 50) {
                Address::factory()->create([
                    'customer_id' => $customer->id,
                ]);
            }

            // Unique (customer_id, product_id) — one review per product per customer
            $reviewCount = rand(0, 3);
            if ($reviewCount > 0) {
                $productIds = Product::query()->inRandomOrder()->limit($reviewCount)->pluck('id');
                foreach ($productIds as $productId) {
                    Review::factory()->create([
                        'customer_id' => $customer->id,
                        'product_id' => $productId,
                    ]);
                }
            }

            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
        $this->command->info('Customers created successfully');
    }
}
