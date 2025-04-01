<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Booking;
use App\Models\Customer;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_can_get_all_bookings(): void
    {
        Booking::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/bookings');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_booking(): void
    {
        $customer = Customer::factory()->create();

        $bookingData = [
            'customer_id' => $customer->id,
            'title' => 'Test Booking',
            'description' => 'Test Description',
            'start_time' => now()->addDay()->toDateTimeString(),
            'end_time' => now()->addDay()->addHours(2)->toDateTimeString(),
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/v1/bookings', $bookingData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Test Booking',
                'status' => 'pending',
            ]);
    }

    public function test_can_update_booking(): void
    {
        $booking = Booking::factory()->create();

        $updateData = [
            'title' => 'Updated Title',
            'status' => 'confirmed',
        ];

        $response = $this->putJson("/api/v1/bookings/{$booking->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'title' => 'Updated Title',
                'status' => 'confirmed',
            ]);
    }

    public function test_can_delete_booking(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->deleteJson("/api/v1/bookings/{$booking->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_can_get_booking_by_id(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->getJson("/api/v1/bookings/{$booking->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $booking->id,
                'title' => $booking->title,
            ]);
    }

    public function test_can_get_bookings_by_customer(): void
    {
        $customer = Customer::factory()->create();
        Booking::factory()->count(3)->create(['customer_id' => $customer->id]);

        $response = $this->getJson("/api/v1/customers/{$customer->id}/bookings");

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_export_bookings_to_csv(): void
    {
        Booking::factory()->count(3)->create();

        $response = $this->getJson("/api/v1/bookings/export/csv");

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->assertHeader('Content-Disposition', 'attachment; filename=bookings.csv');
    }
}
