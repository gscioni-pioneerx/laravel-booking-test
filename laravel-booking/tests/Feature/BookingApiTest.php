<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
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
}
