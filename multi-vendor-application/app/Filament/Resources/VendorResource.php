<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Admin Managment';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function  canViewAny(): bool
    {
        $user = Auth::user();
        if($user->isAdmin()){
            return true;
        }
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make ('Vendor Profile')
                       ->schema ([
                           TextInput::make('store_name')
                                    ->required()
                                    ->maxLength(255),
                           Textarea::make('description')
                                   ->required()
                                   ->columnSpanFull(),
                           Toggle::make('is_active')
                                 ->required(),
                       ])
            ])

            ->columns (1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make ('user.profile.first_name')
                    ->label('Name')
                    ->searchable (),
                TextColumn::make ('user.profile.last_name')
                    ->label('Last Name')
                    ->searchable (),
                TextColumn::make('is_active')
                    ->label('Shop Status')
                    ->badge ()
                    ->state (function ($record){
                        return $record->is_active ? 'Active' : 'Inactive';
                    })
                    ->color (function ($record) {
                        return $record->is_active ? 'success' : 'danger';
                    }),
                TextColumn::make('store_name')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('view_user')
                                     ->label('View User')
                                     ->icon('heroicon-o-user')
                                     ->url(function ($record) {
                        return UserResource::getUrl('edit', ['record' => $record->user_id]);
                    })
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
