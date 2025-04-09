<?php

namespace App\Repository;

use App\Models\Customer; // Import the Customer model
use Illuminate\Database\Eloquent\Collection; // Import the Collection class for returning multiple records

class CustomerRepository
{
    /**
     * Retrieve all customers with selected attributes.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        // Retrieve all customers, only selecting the 'id', 'name', and 'email' fields
        return Customer::all(['id', 'name', 'email']);
    }

    /**
     * Create a new customer record in the database.
     *
     * @param  array  $data
     * @return \App\Models\Customer
     */
    public function create(array $data): Customer
    {
        // Create a new customer record using the provided data and return the newly created customer
        return Customer::create($data);
    }
}
