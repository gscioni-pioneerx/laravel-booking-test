<?php

namespace App\Factories;


use App\Strategies\BookingStatus\BookingStatusStrategy;
use App\Strategies\BookingStatus\CancelledStrategy;
use App\Strategies\BookingStatus\ConfirmedStrategy;
use App\Strategies\BookingStatus\PendingStrategy;

class BookingStatusFactory
{
    public static function createStrategy(string $status): BookingStatusStrategy
    {
        return match ($status) {
            'pending' => new PendingStrategy(),
            'confirmed' => new ConfirmedStrategy(),
            'cancelled' => new CancelledStrategy(),
            default => throw new \InvalidArgumentException("Invalid booking status: {$status}"),
        };
    }
}