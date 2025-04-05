<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test customer index
     */
    public function test_customer_index(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        Customer::factory()->count(5)->create();

        $response = $this->get('/api/customer');
        $response->assertOk()->assertJsonCount(5, 'data');
    }

    /**
     * Test customer store
     */
    public function test_customer_store(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $customerData = [
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
        ];

        $response = $this->post('/api/customer', $customerData);

        $response->assertStatus(201)->assertJsonFragment($customerData);
    }

    /**
     * Test customer show
     */
    public function test_customer_show(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $customer = Customer::factory()->create();
        $response = $this->getJson('/api/customer/'.$customer->id);

        $response->assertOk()->assertJson([
            'data' => [
                'name' => $customer->name,
                'surname' => $customer->surname,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'address' => $customer->address,
            ],
        ]);
    }

    /**
     * Test customer update
     */
    public function test_customer_update(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $customer = Customer::factory()->create();
        $updatedData = [
            'email' => fake()->email(),
            'address' => fake()->address(),
        ];

        $response = $this->putJson('/api/customer/'.$customer->id, $updatedData);

        $response->assertOk()->assertJson([
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'surname' => $customer->surname,
                'email' => $updatedData['email'],
                'phone' => $customer->phone,
                'address' => $updatedData['address'],
            ],
        ]);
    }

    /**
     * Test customer destroy
     */
    public function test_customer_destroy(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $customer = Customer::factory()->create();

        $response = $this->deleteJson('/api/customer/'.$customer->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    /**
     * Test customers csv export
     */
    public function test_customers_export(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        Customer::factory()->count(10)->create();

        $response = $this->getJson('/api/export/customers');

        $response->assertOk()->assertDownload();
    }

    /**
     * Test customer's booking list
     */
    public function test_customers_booking(): void
    {
        Sanctum::actingAs(
            User::factory()->create()
        );

        $customerA = Customer::factory()->create();
        $customerB = Customer::factory()->create();

        $bookingsA = Booking::factory()->count(7)->create([
            'customer_id' => $customerA->id,
        ]);
        $bookingsB = Booking::factory()->count(3)->create([
            'customer_id' => $customerB->id,
        ]);

        $response = $this->getJson('/api/customer/'.$customerA->id.'/bookings');

        $response->assertOk()->assertJsonCount(7, 'data');
    }
}
