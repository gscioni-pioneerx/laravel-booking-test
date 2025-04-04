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

    /**
     * Test booking show
     */
    public function test_booking_show(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $booking = Booking::factory()->create();
        $response = $this->getJson('/api/booking/' . $booking->id);

        $response->assertOk()->assertJson([
            'data' => [
                'customer' => [
                    'id' => $booking->customer->id,
                    'name' => $booking->customer->name,
                    'surname' => $booking->customer->surname,
                    'email' => $booking->customer->email
                ],
                'title' => $booking->title,
                'checkin' => $booking->checkin,
                'checkout' => $booking->checkout
            ]
        ]);
    }

    /**
     * Test booking update
     */
    public function test_booking_update(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $booking = Booking::factory()->create();

        $checkInDate = fake()->dateTimeBetween('now', '+3 months');
        $checkOutDate = (clone $checkInDate)->modify('+' . rand(1, 20) . ' days');

        $updatedData = [
            'checkin' => $checkInDate->format('Y-m-d H:i:s'),
            'checkout' => $checkOutDate->format('Y-m-d H:i:s')
        ];

        $response = $this->putJson('/api/booking/' . $booking->id, $updatedData);

        $response->assertOk()->assertJsonFragment([
            'id' => $booking->id,
            'title' => $booking->title,
            'checkin' => $updatedData['checkin'],
            'checkout' => $updatedData['checkout']
        ]);
    }

    /**
     * Test booking delete
     */
    public function test_booking_delete(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $booking = Booking::factory()->create();
        $response = $this->deleteJson('/api/booking/' . $booking->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id
        ]);
    }

    /**
     * Test bookings export
     */
    public function test_bookings_export(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        Booking::factory()->count(10)->create();

        $response = $this->getJson('/api/export/bookings');

        $response->assertOk()->assertDownload();
    }
}
