<?php

namespace App\Services;

use App\Facades\AppLog;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerService
{
    public function __construct(
        protected CustomerRepository $repository
    ) {
    }

    /**
     * Paginate customers
     *
     * @param integer $perPage
     * @return LengthAwarePaginator
     */
    public function list($perPage = 10): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    /**
     * Get a customer
     *
     * @param int $id
     * @return Customer|null
     */
    public function get(int $id): ?Customer
    {
        return $this->repository->find($id);
    }

    /**
     * Create a customer
     *
     * @param array $data
     * @return Customer
     */
    public function create(array $data): Customer
    {
        $customer = $this->repository->create($data);

        AppLog::info('User {user_id} created customer {customer_id}', [
            'customer_id' => $customer->id
        ]);

        return $customer;
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
        $customer = $this->repository->update($customer, $data);

        AppLog::info('User {user_id} updated customer {customer_id}', [
            'customer_id' => $customer->id
        ]);

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
        $this->repository->delete($customer);

        AppLog::info('User {user_id} deleted customer {customer_id}', [
            'customer_id' => $customer->id
        ]);
    }

    /**
     * Export customers as csv
     *
     * @param string|null $fileName
     * @return string
     */
    public function exportCsv(?string $fileName = null): string
    {
        $fileName = $fileName ?? 'customers_' . time() . '.csv';
        $customers = $this->repository->getAll();

        $filePath = storage_path('app/' . $fileName);
        $file = fopen($filePath, 'w');

        AppLog::info('User {user_id} requested customers csv {file_path}', [
            'file_path' => $filePath
        ]);

        fputcsv($file, [
            'ID',
            'Name',
            'Surname',
            'Email',
            'Phone',
            'Address',
            'Creation Date'
        ]);

        foreach ($customers as $customer) {
            fputcsv($file, [
                $customer->id,
                $customer->name,
                $customer->surname,
                $customer->email,
                $customer->phone,
                $customer->password,
                $customer->created_at
            ]);
        }

        fclose($file);

        return $filePath;
    }
}
