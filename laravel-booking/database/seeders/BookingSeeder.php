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
        $customers = Customer::get();

        if (empty($customers)) {
            return;
        }

        foreach ($customers as $customer) {
            Booking::factory()->count(rand(1, 10))->create([
                'customer_id' => $customer->id,
            ]);
        }
    }
}
