<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

/**
 * @OA\Schema(
 *     schema="UpdateBookingRequest",
 *     @OA\Property(property="customer_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Updated Meeting Title"),
 *     @OA\Property(property="description", type="string", example="Updated description"),
 *     @OA\Property(property="start_time", type="string", format="date-time", example="2023-05-01T10:30:00"),
 *     @OA\Property(property="end_time", type="string", format="date-time", example="2023-05-01T11:30:00"),
 *     @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled"}, example="confirmed")
 * )
 */
class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $booking = $this->route('booking');
        $now = Carbon::now();

        return [
            'customer_id' => 'sometimes|exists:customers,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => [
                'sometimes',
                'date',
                function ($attribute, $value, $fail) use ($booking, $now) {
                    // If updating start_time and it's different from the original
                    if ($value != $booking->start_time) {
                        // Only apply the future date validation for new start times
                        if (Carbon::parse($value)->lt($now)) {
                            $fail('The booking start time must be in the future.');
                        }
                    }
                },
            ],
            'end_time' => [
                'sometimes',
                'date',
                'after:start_time',
            ],
            'status' => [
                'sometimes',
                Rule::in(['pending', 'confirmed', 'cancelled']),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'customer_id.exists' => 'The selected customer does not exist.',
            'title.max' => 'The booking title cannot exceed 255 characters.',
            'start_time.date' => 'The start time must be a valid date.',
            'end_time.date' => 'The end time must be a valid date.',
            'end_time.after' => 'The booking end time must be after the start time.',
            'status.in' => 'The booking status must be one of: pending, confirmed, cancelled.',
        ];
    }
}
