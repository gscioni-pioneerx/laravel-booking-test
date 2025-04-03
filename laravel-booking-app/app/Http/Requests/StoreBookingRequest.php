<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


/**
 * @OA\Schema(
 *     schema="StoreBookingRequest",
 *     required={"customer_id", "title", "start_time", "end_time"},
 *     @OA\Property(property="customer_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Meeting with Client"),
 *     @OA\Property(property="description", type="string", example="Discuss project requirements"),
 *     @OA\Property(property="start_time", type="string", format="date-time", example="2023-05-01T10:00:00"),
 *     @OA\Property(property="end_time", type="string", format="date-time", example="2023-05-01T11:00:00"),
 *     @OA\Property(property="status", type="string", enum={"pending", "confirmed", "cancelled"}, example="pending")
 * )
 */
class StoreBookingRequest extends FormRequest
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
        return [
            'customer_id' => 'required|exists:customers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => [
                'required',
                'date',
                'after_or_equal:now', // Ensure start_time is not in the past
            ],
            'end_time' => [
                'required',
                'date',
                'after:start_time', // Ensure end_time is after start_time
            ],
            'status' => [
                'required',
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
            'customer_id.required' => 'A customer must be selected.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'title.required' => 'The booking title is required.',
            'title.max' => 'The booking title cannot exceed 255 characters.',
            'start_time.required' => 'The start time is required.',
            'start_time.date' => 'The start time must be a valid date.',
            'start_time.after_or_equal' => 'The booking start time must be in the future.',
            'end_time.required' => 'The end time is required.',
            'end_time.date' => 'The end time must be a valid date.',
            'end_time.after' => 'The booking end time must be after the start time.',
            'status.required' => 'The booking status is required.',
            'status.in' => 'The booking status must be one of: pending, confirmed, cancelled.',
        ];
    }
}