<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        $bookings = $this->bookingService->list($perPage);

        return BookingResource::collection($bookings);
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

        $booking = $this->bookingService->create($validated);

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

        $this->bookingService->update($booking, $validated);

        return new BookingResource($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $this->bookingService->delete($booking);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Export all the data in csv
     */
    public function export()
    {
        $filePath = $this->bookingService->exportCsv();

        return response()->download($filePath, 'bookings.csv')->deleteFileAfterSend(true);
    }
}
