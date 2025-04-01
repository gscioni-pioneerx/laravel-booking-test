<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingRepository
{

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Booking::with('customer')->paginate($perPage);
    }

    public function findById(int $id): ?Booking
    {
        return Booking::with('customer')->find($id);
    }

    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function update(Booking $booking, array $data): bool
    {
        return $booking->update($data);
    }

    public function delete(Booking $booking): bool
    {
        return $booking->delete();
    }

    public function getByCustomerId(int $customerId): Collection
    {
        return Booking::where('customer_id', $customerId)->get();
    }
}