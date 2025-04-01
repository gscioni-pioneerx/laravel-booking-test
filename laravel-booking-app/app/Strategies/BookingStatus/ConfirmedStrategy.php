<?php

namespace App\Strategies\BookingStatus;

use App\Models\Booking;


class ConfirmedStrategy implements BookingStatusStrategy
{
    public function handle(Booking $booking): bool
    {
        // Logic for handling confirmed bookings
        return true;
    }

    public function getNotificationMessage(Booking $booking): string
    {
        return "Your booking #{$booking->id} has been confirmed.";
    }
}