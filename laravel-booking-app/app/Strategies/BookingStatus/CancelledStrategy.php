<?php

namespace App\Strategies\BookingStatus;

use App\Models\Booking;

class CancelledStrategy implements BookingStatusStrategy
{
    public function handle(Booking $booking): bool
    {
        // Logic for handling cancelled bookings
        return true;
    }

    public function getNotificationMessage(Booking $booking): string
    {
        return "Your booking #{$booking->id} has been cancelled.";
    }
}