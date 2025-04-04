<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerService
{
    public function __construct(
        protected CustomerRepository $repository
    ) {
    }

    public function list($perPage = 10): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function get(int $id): ?Customer
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Customer
    {
        return $this->repository->create($data);
    }

    public function update(Customer $customer, array $data): Customer
    {
        return $this->repository->update($customer, $data);
    }

    public function delete(Customer $customer): void
    {
        $this->repository->delete($customer);
    }

    public function exportCsv(?string $fileName = null): string
    {
        $fileName = $fileName ?? 'customers_' . time() . '.csv';
        $customers = $this->repository->getAll();

        $filePath = storage_path('app/' . $fileName);
        $file = fopen($filePath, 'w');

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
