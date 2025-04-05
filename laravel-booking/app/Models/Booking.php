<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Booking
 *
 * @property int $id
 * @property object $customer
 * @property int $customer_id
 * @property string $checkin
 * @property string $checkout
 * @property string $created_at
 * @property string $updated_at
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'title',
        'status',
        'checkin',
        'checkout',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
