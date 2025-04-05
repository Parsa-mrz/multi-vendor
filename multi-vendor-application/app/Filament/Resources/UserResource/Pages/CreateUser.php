<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->role === 'vendor' && ! $this->record->vendor) {
            $this->record->vendor()->create([
                'store_name' => $this->record->vendor['store_name'] ?? 'Default Store Name',
                'description' => $this->record->vendor['description'] ?? 'Default vendor description.',
                'is_active' => $this->record->vendor['is_active'] ?? false,
            ]);
        }
    }
}
