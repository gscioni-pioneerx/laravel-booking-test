<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository
{
    /**
     * Get all customers
     */
    public function getAll(): Collection
    {
        return Customer::get();
    }

    /**
     * Paginate customers
     *
     * @param  int  $perPage
     */
    public function paginate($perPage = 10): LengthAwarePaginator
    {
        return Customer::paginate($perPage);
    }

    /**
     * Find a customer by id
     */
    public function find(int $id): ?Customer
    {
        return Customer::find($id);
    }

    /**
     * Create a new customer
     */
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    /**
     * Update a customer
     */
    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer;
    }

    /**
     * Delete a customer
     */
    public function delete(Customer $customer): void
    {
        $customer->delete();
    }
}
