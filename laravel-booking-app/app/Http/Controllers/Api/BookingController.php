<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


/**
 * @OA\Info(
 *     title="Booking API",
 *     version="1.0.0",
 *     description="API for managing bookings"
 * )
 */
class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/bookings",
     *     summary="Get all bookings",
     *     tags={"Bookings"},
     *     @OA\Response(
     *         response=200,
     *         description="List of bookings"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $bookings = $this->bookingService->getAllBookings();
        return response()->json($bookings->items());
    }

    /**
     * @OA\Get(
     *     path="/api/v1/bookings/active",
     *     summary="Get all currently active bookings",
     *     tags={"Bookings"},
     *     @OA\Response(
     *         response=200,
     *         description="List of active bookings"
     *     )
     * )
     */
    public function getActiveBookings(): JsonResponse
    {
        $bookings = Booking::with('customer')->active()->get();
        return response()->json($bookings);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/bookings/future",
     *     summary="Get all future bookings",
     *     tags={"Bookings"},
     *     @OA\Response(
     *         response=200,
     *         description="List of future bookings"
     *     )
     * )
     */
    public function getFutureBookings(): JsonResponse
    {
        $bookings = Booking::with('customer')->future()->get();
        return response()->json($bookings);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/bookings/status/{status}",
     *     summary="Get bookings by status",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", enum={"pending", "confirmed", "cancelled"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of bookings with specified status"
     *     )
     * )
     */
    public function getBookingsByStatus(string $status): JsonResponse
    {
        $validStatuses = ['pending', 'confirmed', 'cancelled'];

        if (!in_array($status, $validStatuses)) {
            return response()->json([
                'message' => 'Invalid status. Must be one of: ' . implode(', ', $validStatuses)
            ], Response::HTTP_BAD_REQUEST);
        }

        $bookings = Booking::with('customer')->withStatus($status)->get();
        return response()->json($bookings);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/bookings",
     *     summary="Create a new booking",
     *     tags={"Bookings"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreBookingRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Booking created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());
            return response()->json($booking, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => ['general' => [$e->getMessage()]]
            ], Response::HTTP_CONFLICT);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/bookings/{id}",
     *     summary="Get a booking by ID",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $booking = $this->bookingService->getBookingById($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($booking);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/bookings/{id}",
     *     summary="Update a booking",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateBookingRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Booking updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Booking not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(UpdateBookingRequest $request, Booking $booking): JsonResponse
    {
        try {
            $this->bookingService->updateBooking($booking, $request->validated());
            return response()->json($booking->fresh());
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => ['general' => [$e->getMessage()]]
            ], Response::HTTP_CONFLICT);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/bookings/export/csv",
     *     summary="Export bookings to CSV",
     *     tags={"Bookings"},
     *     @OA\Response(
     *         response=200,
     *         description="CSV file download",
     *         @OA\Header(
     *             header="Content-Type",
     *             description="text/csv",
     *             @OA\Schema(type="string")
     *         ),
     *         @OA\Header(
     *             header="Content-Disposition",
     *             description="attachment; filename=bookings.csv",
     *             @OA\Schema(type="string")
     *         )
     *     )
     * )
     */
    public function exportCsv(): StreamedResponse
    {
        $csvFileName = $this->bookingService->exportBookingsToCsv();
        return Storage::download($csvFileName, 'bookings.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/customers/{customerId}/bookings",
     *     summary="Get bookings by customer ID",
     *     tags={"Bookings"},
     *     @OA\Parameter(
     *         name="customerId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of bookings for the customer"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Customer not found"
     *     )
     * )
     */
    public function getBookingsByCustomer(int $customerId): JsonResponse
    {
        $bookings = $this->bookingService->getBookingsByCustomerId($customerId);
        return response()->json($bookings);
    }
}