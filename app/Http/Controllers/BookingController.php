<?php

namespace App\Http\Controllers;

use App\Models\Booking; // Import the Booking model
use App\Http\Requests\StoreBookingRequest; // Import request for validating store action
use App\Http\Requests\UpdateBookingRequest; // Import request for validating update action
use Illuminate\Support\Facades\Log; // Import the Log facade for logging actions
use App\Repository\BookingRepository; // Import the BookingRepository for handling database operations

class BookingController extends Controller
{
    // Declare a protected property to hold the injected BookingRepository instance
    protected BookingRepository $repo;

    /**
     * Constructor to inject the BookingRepository dependency
     *
     * @param  \App\Repository\BookingRepository  $repo
     */
    public function __construct(BookingRepository $repo)
    {
        // Initialize the repository with the provided instance
        $this->repo = $repo;
    }

    /**
     * Display a listing of the bookings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Call the repository's 'all' method to retrieve all bookings
        return $this->repo->all();
    }

    /**
     * Store a newly created booking in the database.
     *
     * @param  \App\Http\Requests\StoreBookingRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBookingRequest $request)
    {
        // Create a new booking record using validated data from the request
        $booking = $this->repo->create($request->validated());

        // Log the successful creation of a new booking
        Log::info('Booking created', ['id' => $booking->id]);

        // Return the newly created booking with a 201 status
        return response()->json($booking, 201);
    }

    /**
     * Display the specified booking.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Attempt to find the booking by its ID
        $booking = $this->repo->find($id);

        // If the booking isn't found, return a 404 not found response
        if (!$booking) return response()->json(['message' => 'Not found'], 404);

        // Return the found booking as a JSON response
        return $booking;
    }

    /**
     * Update the specified booking in the database.
     *
     * @param  \App\Http\Requests\UpdateBookingRequest  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        // Update the booking with the validated data from the request
        $booking = $this->repo->update($booking, $request->validated());

        // Log the successful update of the booking
        Log::info('Booking updated', ['id' => $booking->id]);

        // Return the updated booking as a JSON response
        return $booking;
    }

    /**
     * Remove the specified booking from the database.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        // Delete the specified booking using the repository
        $this->repo->delete($booking);

        // Log the deletion of the booking
        Log::info('Booking deleted', ['id' => $booking->id]);

        // Return a response with no content (204)
        return response()->noContent();
    }
}
