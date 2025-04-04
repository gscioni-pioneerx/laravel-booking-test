<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test booking index
     */
    public function test_booking_index(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        Booking::factory()->count(5)->create();

        $response = $this->get('/api/booking');

        $response->assertOk()->assertJsonCount(5, 'data');
    }

    /**
     * Test booking store
     */
    public function test_booking_store(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $customer = Customer::factory()->create();

        $checkInDate = fake()->dateTimeBetween('now', '+3 months');
        $checkOutDate = (clone $checkInDate)->modify('+' . rand(1, 20) . ' days');

        $bookingData = [
            'customer_id' => $customer->id,
            'title' => fake()->text(100),
            'checkin' => $checkInDate->format('Y-m-d H:i:s'),
            'checkout' => $checkOutDate->format('Y-m-d H:i:s')
        ];

        $response = $this->postJson('/api/booking', $bookingData);

        $response->assertStatus(201)->assertJsonFragment([
            'title' => $bookingData['title'],
            'checkin' => $bookingData['checkin'],
            'checkout' => $bookingData['checkout']
        ]);
    }
}
