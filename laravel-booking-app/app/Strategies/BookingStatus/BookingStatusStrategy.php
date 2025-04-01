<?php

namespace App\Strategies\BookingStatus;

use App\Models\Booking;

interface BookingStatusStrategy
{
    public function handle(Booking $booking): bool;
    public function getNotificationMessage(Booking $booking): string;
}