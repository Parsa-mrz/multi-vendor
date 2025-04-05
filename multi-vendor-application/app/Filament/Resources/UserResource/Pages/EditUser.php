<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->role === 'vendor' && !$this->record->vendor) {
            $this->record->vendor()->create([
                'store_name' => $this->record->vendor['store_name'] ?? 'Default Store Name',
                'description' => $this->record->vendor['description'] ?? 'Default vendor description.',
                'is_active' => $this->record->vendor['is_active'],
            ]);
        }
    }
}
