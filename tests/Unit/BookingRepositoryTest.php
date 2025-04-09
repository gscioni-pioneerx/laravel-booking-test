<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Repository\BookingRepository;

class BookingRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected BookingRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new BookingRepository();
    }

    public function test_can_create_booking()
    {
        $customer = Customer::factory()->create();

        $data = [
            'customer_id' => $customer->id,
            'booking_datetime' => now()->addDay(),
            'status' => 'confirmed'
        ];

        $booking = $this->repo->create($data);

        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);
    }

    public function test_can_update_booking()
    {
        $booking = Booking::factory()->create([
            'status' => 'confirmed'
        ]);

        $updated = $this->repo->update($booking, ['status' => 'cancelled']);

        $this->assertEquals('cancelled', $updated->status);
    }

    public function test_can_delete_booking()
    {
        $booking = Booking::factory()->create();

        $result = $this->repo->delete($booking);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_can_find_booking()
    {
        $booking = Booking::factory()->create();

        $found = $this->repo->find($booking->id);

        $this->assertNotNull($found);
        $this->assertEquals($booking->id, $found->id);
    }
}
