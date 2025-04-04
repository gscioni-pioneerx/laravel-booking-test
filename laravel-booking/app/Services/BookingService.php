<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookingService
{
    public function __construct(
        protected BookingRepository $repository
    ) {
    }

    public function list($perPage = 10): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    public function get(int $id): ?Booking
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Booking
    {
        return $this->repository->create($data);
    }

    public function update(Booking $booking, array $data): Booking
    {
        return $this->repository->update($booking, $data);
    }

    public function delete(Booking $booking): void
    {
        $this->repository->delete($booking);
    }
}
