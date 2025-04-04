<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
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
            'address' => fake()->address()
        ];

        $response = $this->post('/api/customer', $customerData);

        $response->assertStatus(201)->assertJsonFragment($customerData);
    }
}
