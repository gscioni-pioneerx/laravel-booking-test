<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Customer;
use App\Repository\CustomerRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CustomerRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new CustomerRepository();
    }

    public function test_can_create_customer()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $customer = $this->repo->create($data);

        $this->assertDatabaseHas('customers', ['email' => 'john@example.com']);
        $this->assertEquals('John Doe', $customer->name);
    }

    public function test_can_list_customers()
    {
        Customer::factory()->count(3)->create();

        $all = $this->repo->all();

        $this->assertCount(3, $all);
    }
}
