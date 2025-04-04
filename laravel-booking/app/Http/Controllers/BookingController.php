<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        $bookings = Booking::paginate($perPage);

        return BookingResource::collection($bookings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin'
        ]);

        $booking = Booking::create($validated);

        return new BookingResource($booking);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        return new BookingResource($booking);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|required|exists:customers,id',
            'title' => 'sometimes|required|max:255',
            'checkin' => 'sometimes|required|date|after_or_equal:today',
            'checkout' => 'sometimes|required|date|after:checkin'
        ]);

        $booking->update($validated);

        return new BookingResource($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json([
            'message' => 'Booking deleted successfully.'
        ], 204);
    }
}
