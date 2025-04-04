<?php

namespace App\Services;

use App\Facades\AppLog;
use App\Models\Booking;
use App\Repositories\BookingRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BookingService
{
    public function __construct(
        protected BookingRepository $repository
    ) {
    }

    /**
     * Paginate booking items
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function list($perPage = 10): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    /**
     * Get a booking item
     *
     * @param int $id
     * @return Booking|null
     */
    public function get(int $id): ?Booking
    {
        return $this->repository->find($id);
    }

    /**
     * Create a booking item
     *
     * @param array $data
     * @return Booking
     */
    public function create(array $data): Booking
    {
        $booking = $this->repository->create($data);

        AppLog::info('User {user_id} created booking {booking_id} for customer {customer_id}', [
            'booking_id' => $booking->id,
            'customer_id' => $booking->customer->id
        ]);

        return $booking;
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
        $booking = $this->repository->update($booking, $data);

        AppLog::info('User {user_id} updated booking {booking_id}', [
            'booking_id' => $booking->id
        ]);

        return $booking;
    }

    /**
     * Delete a booking item
     *
     * @param Booking $booking
     * @return string
     */
    public function delete(Booking $booking): void
    {
        $this->repository->delete($booking);

        AppLog::info('User {user_id} deleted booking {booking_id}', [
            'booking_id' => $booking->id
        ]);
    }

    /**
     * Export bookings as csv
     *
     * @param string|null $fileName
     * @return string
     */
    public function exportCsv(?string $fileName = null): string
    {
        $fileName = $fileName ?? 'bookings_' . time() . '.csv';
        $bookings = $this->repository->getAll();

        $filePath = storage_path('app/' . $fileName);
        $file = fopen($filePath, 'w');

        AppLog::info('User {user_id} requested bookings csv export {file_path}', [
            'file_path' => $filePath
        ]);

        fputcsv($file, [
            'ID',
            'Customer Full Name',
            'Customer Email',
            'Booking Title',
            'Check-in',
            'Check-out',
            'Status',
            'Creation Date'
        ]);

        foreach ($bookings as $booking) {
            $checkin = date('d-m-Y H:i', strtotime($booking->checkin));
            $checkout = date('d-m-Y H:i', strtotime($booking->checkout));

            fputcsv($file, [
                $booking->id,
                $booking->customer->name . ' ' . $booking->customer->surname,
                $booking->customer->email,
                $booking->title,
                $checkin,
                $checkout,
                $booking->status,
                $booking->created_at
            ]);
        }

        fclose($file);

        return $filePath;
    }
}
