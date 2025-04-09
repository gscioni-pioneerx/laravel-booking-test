<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Import the base Eloquent Model class
use Illuminate\Database\Eloquent\Factories\HasFactory; // Import the HasFactory trait for factory support
use App\Enum\BookingStatus; // Import the custom enum for booking statuses

class Booking extends Model
{
    // Use the HasFactory trait to enable factory functionality for creating Booking instances in tests
    use HasFactory;

    // The attributes that are mass assignable
    protected $fillable = ['customer_id', 'booking_datetime', 'status'];

    // The attributes that should be cast to native types
    protected $casts = [
        'status' => BookingStatus::class, // Cast the 'status' attribute to the BookingStatus enum
    ];

    /**
     * Define the relationship between Booking and Customer.
     *
     * A booking belongs to a customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        // Define the inverse of the one-to-many relationship: each booking belongs to one customer
        return $this->belongsTo(Customer::class);
    }
}
