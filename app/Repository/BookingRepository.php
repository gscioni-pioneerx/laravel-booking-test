<?php

namespace App\Repository;

use App\Models\Booking; // Import the Booking model
use Illuminate\Database\Eloquent\Collection; // Import Collection for returning multiple records

class BookingRepository
{
    /**
     * Retrieve all bookings with their associated customers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        // Retrieve all bookings along with the associated customer data using eager loading
        return Booking::with('customer')->get();
    }

    /**
     * Retrieve a booking by its ID, with the associated customer.
     *
     * @param  int  $id
     * @return \App\Models\Booking|null
     */
    public function find(int $id): ?Booking
    {
        // Find a booking by its ID and load the associated customer data
        return Booking::with('customer')->find($id);
    }

    /**
     * Create a new booking record in the database.
     *
     * @param  array  $data
     * @return \App\Models\Booking
     */
    public function create(array $data): Booking
    {
        // Create a new booking using the provided data
        return Booking::create($data);
    }

    /**
     * Update an existing booking record.
     *
     * @param  \App\Models\Booking  $booking
     * @param  array  $data
     * @return \App\Models\Booking
     */
    public function update(Booking $booking, array $data): Booking
    {
        // Update the booking with the new data
        $booking->update($data);
        return $booking; // Return the updated booking instance
    }

    /**
     * Delete a booking record from the database.
     *
     * @param  \App\Models\Booking  $booking
     * @return bool
     */
    public function delete(Booking $booking): bool
    {
        // Delete the specified booking record and return the result (true/false)
        return $booking->delete();
    }
}
