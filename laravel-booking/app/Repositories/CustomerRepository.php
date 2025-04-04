<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerRepository
{
    /**
     * Get all customers
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 10): LengthAwarePaginator
    {
        return Customer::paginate($perPage);
    }

    /**
     * Find a customer by id
     *
     * @param int $id
     * @return Customer|null
     */
    public function find(int $id): ?Customer
    {
        return Customer::find($id);
    }

    /**
     * Create a new customer
     *
     * @param array $data
     * @return Customer
     */
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    /**
     * Update a customer
     *
     * @param Customer $customer
     * @param array $data
     * @return Customer
     */
    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer;
    }

    /**
     * Delete a customer
     *
     * @param Customer $customer
     * @return void
     */
    public function delete(Customer $customer): void
    {
        $customer->delete();
    }
}
