<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'last_login' => $this->last_login?->toDateTimeString(),
            'store_name' => $this->when(
                $this->role === 'vendor',
                $this->vendor?->store_name
            ),
            'description' => $this->when(
                $this->role === 'vendor',
                $this->vendor?->description
            ),
            'vendor' => $this->whenLoaded(
                'vendor',
                fn () => new VendorResource($this->vendor)
            ),
            'profile' => $this->whenLoaded(
                'profile',
                fn () => new ProfileResource($this->profile)
            ),
        ];
    }
}
