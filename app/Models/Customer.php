<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import the HasFactory trait for factory support
use Illuminate\Database\Eloquent\Model; // Import the base Eloquent Model class

class Customer extends Model
{
    // Use the HasFactory trait to enable factory functionality for creating Customer instances in tests
    use HasFactory;

    // The attributes that are mass assignable
    protected $fillable = ['name', 'email'];

    /**
     * Define the relationship between Customer and Booking.
     *
     * A customer can have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        // Define the one-to-many relationship: a customer can have many bookings
        return $this->hasMany(Booking::class);
    }
}
