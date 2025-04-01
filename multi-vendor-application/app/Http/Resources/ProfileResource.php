<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'profileable_type' => $this->profileable_type,
            'profileable_id' => $this->profileable_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
            'profileable' => $this->whenLoaded(
                'profileable',
                fn () => $this->profileable_type === 'App\Models\Vendor'
                    ? new VendorResource($this->profileable)
                    : null
            ),
        ];
    }
}
