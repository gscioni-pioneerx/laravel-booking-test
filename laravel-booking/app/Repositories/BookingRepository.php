<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository
{
    /**
     * List all booking items
     *
     * @return Collection<Booking>
     */
    public function getAll(): Collection
    {
        return Booking::with('customer')->get();
    }

    /**
     * Get all booking items
     *
     * @param  int  $perPage
     */
    public function paginate($perPage = 10): LengthAwarePaginator
    {
        return Booking::with('customer')->paginate($perPage);
    }

    /**
     * Find a booking item by id
     */
    public function find(int $id): ?Booking
    {
        return Booking::with('customer')->find($id);
    }

    /**
     * Create a new booking item
     */
    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    /**
     * Update a booking item
     */
    public function update(Booking $booking, array $data): Booking
    {
        $booking->update($data);

        return $booking;
    }

    /**
     * Delete a booking item
     */
    public function delete(Booking $booking): void
    {
        $booking->delete();
    }
}
