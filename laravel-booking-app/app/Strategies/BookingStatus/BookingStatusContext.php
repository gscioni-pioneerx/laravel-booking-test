<?php

namespace App\Strategies\BookingStatus;


use App\Factories\BookingStatusFactory;
use App\Models\Booking;

class BookingStatusContext
{
    private BookingStatusStrategy $strategy;

    public function setStrategy(string $status): void
    {
        $this->strategy = BookingStatusFactory::createStrategy($status);
    }

    public function executeStrategy(Booking $booking): bool
    {
        return $this->strategy->handle($booking);
    }

    public function getNotificationMessage(Booking $booking): string
    {
        return $this->strategy->getNotificationMessage($booking);
    }
}