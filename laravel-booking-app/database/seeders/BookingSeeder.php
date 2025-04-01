<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        if ($customers->count() === 0) {
            $this->command->info('No customers found. Please run the CustomerSeeder first.');
            return;
        }

        foreach ($customers as $customer) {
            Booking::factory()->count(rand(1, 5))->create([
                'customer_id' => $customer->id,
            ]);
        }
    }
}