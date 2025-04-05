<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make (),
        ];
    }

    public function getRecord(): \Illuminate\Database\Eloquent\Model
    {
        $user = Auth::user();
        $product = parent::getRecord();

        if ($user->isVendor() && $product->vendor_id !== $user->vendor->id) {
            throw new ModelNotFoundException('You are not authorized to edit this product');
        }

        return $product;
    }
}
