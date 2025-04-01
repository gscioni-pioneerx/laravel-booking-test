<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Strategies\BookingStatus\BookingStatusContext;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingService
{
    protected BookingRepository $bookingRepository;
    protected LoggingService $loggingService;
    protected BookingStatusContext $statusContext;

    public function __construct(
        BookingRepository $bookingRepository,
        LoggingService $loggingService,
        BookingStatusContext $statusContext
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->loggingService = $loggingService;
        $this->statusContext = $statusContext;
    }

    public function getAllBookings(int $perPage = 15): LengthAwarePaginator
    {
        return $this->bookingRepository->getAll($perPage);
    }

    public function getBookingById(int $id): ?Booking
    {
        return $this->bookingRepository->findById($id);
    }

    public function createBooking(array $data): Booking
    {
        $this->checkForOverlappingBookings($data);

        $booking = $this->bookingRepository->create($data);

        // Process the booking based on its status
        $this->statusContext->setStrategy($booking->status);
        $this->statusContext->executeStrategy($booking);

        $this->loggingService->logOperation('created', 'booking', $booking->id, $data);

        return $booking;
    }

    public function updateBooking(Booking $booking, array $data): bool
    {
        if (isset($data['start_time']) || isset($data['end_time'])) {
            $checkData = [
                'start_time' => $data['start_time'] ?? $booking->start_time,
                'end_time' => $data['end_time'] ?? $booking->end_time,
                'id' => $booking->id, // Exclude current booking from overlap check
            ];
            $this->checkForOverlappingBookings($checkData);
        }

        $oldStatus = $booking->status;
        $result = $this->bookingRepository->update($booking, $data);

        // If status has changed, process with the appropriate strategy
        if (isset($data['status']) && $oldStatus !== $data['status']) {
            $this->statusContext->setStrategy($booking->status);
            $this->statusContext->executeStrategy($booking);
        }

        $this->loggingService->logOperation('updated', 'booking', $booking->id, $data);

        return $result;
    }

    public function deleteBooking(Booking $booking): bool
    {
        $result = $this->bookingRepository->delete($booking);
        $this->loggingService->logOperation('deleted', 'booking', $booking->id);
        return $result;
    }

    public function getBookingsByCustomerId(int $customerId): Collection
    {
        return $this->bookingRepository->getByCustomerId($customerId);
    }

    /**
     * Check for overlapping bookings
     * 
     * @param array $data Booking data with start_time and end_time
     * @throws \Exception If overlapping bookings are found
     */
    protected function checkForOverlappingBookings(array $data): void
    {
        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);

        $query = Booking::where(function ($query) use ($startTime, $endTime) {
            // Find bookings where:
            // 1. New booking starts during an existing booking
            // 2. New booking ends during an existing booking
            // 3. New booking completely contains an existing booking
            $query->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<=', $startTime)
                    ->where('end_time', '>', $startTime);
            })->orWhere(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                    ->where('end_time', '>=', $endTime);
            })->orWhere(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '>=', $startTime)
                    ->where('end_time', '<=', $endTime);
            });
        });

        // Exclude current booking if updating
        if (isset($data['id'])) {
            $query->where('id', '!=', $data['id']);
        }

        $overlappingBookings = $query->count();

        if ($overlappingBookings > 0) {
            throw new \Exception('The requested time slot overlaps with existing bookings.');
        }
    }

    public function exportBookingsToCsv(): string
    {
        $bookings = Booking::with('customer')->get();
        $csvFileName = 'bookings_' . time() . '.csv';
        $csvPath = storage_path('app/' . $csvFileName);

        $file = fopen($csvPath, 'w');

        // Add headers with more descriptive names
        fputcsv($file, [
            'Booking ID',
            'Customer Name',
            'Booking Title',
            'Description',
            'Start Date/Time',
            'End Date/Time',
            'Status',
            'Created On'
        ]);

        foreach ($bookings as $booking) {
            // Format dates for better readability
            $startTime = $booking->start_time ? date('Y-m-d H:i', strtotime($booking->start_time)) : '';
            $endTime = $booking->end_time ? date('Y-m-d H:i', strtotime($booking->end_time)) : '';
            $createdAt = date('Y-m-d H:i', strtotime($booking->created_at));

            // Capitalize status
            $status = ucfirst($booking->status);

            // Truncate description if too long
            $description = strlen($booking->description) > 100
                ? substr($booking->description, 0, 97) . '...'
                : $booking->description;

            fputcsv($file, [
                $booking->id,
                $booking->customer->name,
                $booking->title,
                $description,
                $startTime,
                $endTime,
                $status,
                $createdAt
            ]);
        }

        fclose($file);

        $this->loggingService->logOperation('exported', 'bookings', 0, ['format' => 'csv']);

        return $csvFileName;
    }
}