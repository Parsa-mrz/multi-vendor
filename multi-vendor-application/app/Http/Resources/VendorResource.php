<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class VendorResource
 *
 * Transforms the Vendor model into an API-friendly array format.
 * It includes the `store_name`, `description`, and `profile` resource,
 * with conditional loading of the profile relationship.
 */
class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * This method converts the Vendor model into an array, including relevant fields
     * such as `store_name` and `description`. It conditionally loads the `ProfileResource`
     * if the `profile` relationship is loaded.
     *
     * @param  Request  $request  The incoming HTTP request.
     * @return array<string, mixed> The transformed array representation of the vendor.
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
