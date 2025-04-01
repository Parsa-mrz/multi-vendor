<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
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
            'is_active' => $this->is_active,
            'store_name' => $this->store_name,
            'description' => $this->description,
            'profile' => $this->whenLoaded(
                'profile',
                fn () => new ProfileResource($this->profile)
            ),
        ];
    }
}
