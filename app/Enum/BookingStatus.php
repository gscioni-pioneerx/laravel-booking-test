<?php

namespace App\Enum;

enum BookingStatus: string
{
    case Created = 'created';
    case Confirmed = 'confirmed';
    case Canceled = 'canceled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
