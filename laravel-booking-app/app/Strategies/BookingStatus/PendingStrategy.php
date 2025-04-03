<?php

namespace App\Strategies\BookingStatus;

use App\Models\Booking;

class PendingStrategy implements BookingStatusStrategy
{
    public function handle(Booking $booking): bool
    {
        // Logic for handling pending bookings
        return true;
    }

    public function getNotificationMessage(Booking $booking): string
    {
        return "Your booking #{$booking->id} is pending confirmation.";
    }
}