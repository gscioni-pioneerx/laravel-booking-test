<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkInDate = fake()->dateTimeBetween('now', '+3 months');
        $checkOutDate = (clone $checkInDate)->modify('+'.rand(1, 20).' days');

        return [
            'customer_id' => Customer::factory(),
            'title' => fake()->text(100),
            'checkin' => $checkInDate->format('Y-m-d H:i:s'),
            'checkout' => $checkOutDate->format('Y-m-d H:i:s'),
        ];
    }
}
