<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * App\Http\Resources
 *
 * @property int $id
 * @property object $customer
 * @property string $title
 * @property string $status
 * @property string $checkin
 * @property string $checkout
 * @property string $created_at
 * @property string $updated_at
 */
class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => [
                'id' => $this->customer->id,
                'name' => $this->customer->name,
                'surname' => $this->customer->surname,
                'email' => $this->customer->email,
            ],
            'title' => $this->title,
            'status' => $this->status,
            'checkin' => $this->checkin,
            'checkout' => $this->checkout,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
