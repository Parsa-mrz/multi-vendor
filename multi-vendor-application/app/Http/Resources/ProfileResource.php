<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProfileResource
 *
 * Transform the Profile model data into an API-friendly array format.
 * The ProfileResource can also include a nested Vendor resource when the profileable
 * type is 'App\Models\Vendor'.
 *
 * @package App\Http\Resources
 */
class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * This method converts the User model into an array, including relevant fields
     * and conditionally including nested resources such as `store_name` and `description`
     * if the user's role is 'vendor'. It also conditionally loads the `VendorResource` and
     * `ProfileResource` if these relationships are loaded.
     *
     * @param Request $request The incoming HTTP request.
     *
     * @return array<string, mixed> The transformed array representation of the user.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone_number' => $this->phone_number,
        ];
    }
}
