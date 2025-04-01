<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_can_get_all_customers(): void
    {
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/customers');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_customer(): void
    {
        $customerData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
        ];

        $response = $this->postJson('/api/v1/customers', $customerData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ]);
    }

    public function test_can_update_customer(): void
    {
        $customer = Customer::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'phone' => '9876543210',
        ];

        $response = $this->putJson("/api/v1/customers/{$customer->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Updated Name',
                'phone' => '9876543210',
            ]);
    }

    public function test_can_delete_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/v1/customers/{$customer->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_can_get_customer_by_id(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/v1/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $customer->id,
                'name' => $customer->name,
            ]);
    }

    public function test_cannot_create_customer_with_duplicate_email(): void
    {
        Customer::factory()->create(['email' => 'duplicate@example.com']);

        $customerData = [
            'name' => 'Another User',
            'email' => 'duplicate@example.com',
            'phone' => '1234567890',
        ];

        $response = $this->postJson('/api/v1/customers', $customerData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}