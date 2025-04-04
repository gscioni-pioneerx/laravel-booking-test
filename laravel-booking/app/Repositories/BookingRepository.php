<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookingRepository
{
    /**
     * Get all booking items
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 10): LengthAwarePaginator
    {
        return Booking::with('customer')->paginate($perPage);
    }

    /**
     * Find a booking item by id
     *
     * @param int $id
     * @return Booking|null
     */
    public function find(int $id): ?Booking
    {
        return Booking::with('customer')->find($id);
    }

    /**
     * Create a new booking item
     *
     * @param array $data
     * @return Booking
     */
    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    /**
     * Update a booking item
     *
     * @param Booking $booking
     * @param array $data
     * @return Booking
     */
    public function update(Booking $booking, array $data): Booking
    {
        $booking->update($data);

        return $booking;
    }

    /**
     * Delete a booking item
     *
     * @param Booking $booking
     * @return void
     */
    public function delete(Booking $booking): void
    {
        $booking->delete();
    }
}
