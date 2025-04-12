<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Policies\OrderPolicy;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop Management';

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return static::getModel()::count();
        }
        if ($user->isVendor()) {
            return Order::whereHas('items.product', function ($q) use ($user) {
                $q->where('vendor_id', $user->vendor->id);
            })->count();
        }
        if ($user->isCustomer()) {
            return $user->orders->count();
        }
        return '';
    }

    public static function getEloquentQuery(): Builder
    {
        return app(OrderPolicy::class)->scope(
            Auth::user(),
            parent::getEloquentQuery()->with('items.product')
        );
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order Details')
                    ->schema([
                        TextInput::make('order_number')
                            ->required()
                            ->disabled()
                            ->maxLength(255),
                        TextInput::make('status')
                            ->required()
                            ->disabled()
                            ->maxLength(255)
                            ->default('pending'),
                        TextInput::make('subtotal')
                            ->required()
                            ->disabled()
                            ->numeric(),
                        TextInput::make('total')
                            ->required()
                            ->disabled()
                            ->numeric(),
                        TextInput::make('payment_method')
                            ->disabled()
                            ->maxLength(255),
                        TextInput::make('payment_status')
                            ->required()
                            ->disabled()
                            ->maxLength(255)
                            ->default('pending'),
                        TextInput::make('transaction_id')
                            ->maxLength(255)
                            ->disabled(),
                        Textarea::make ('address')
                            ->disabled (),
                        Textarea::make('notes')
                            ->columnSpanFull()
                            ->disabled(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Order Items')
                    ->collapsible()
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->columns(5)
                            ->schema([
                                Placeholder::make('product.name')
                                    ->label('Product Name')
                                    ->content(fn ($record) => $record->product?->name ?? 'N/A'),
                                Placeholder::make('quantity')
                                    ->label('Quantity')
                                    ->content(fn ($record) => $record->quantity ?? 'N/A'),
                                Placeholder::make('price')
                                    ->label('Price')
                                    ->content(fn ($record) => $record->price ?? 'N/A'),
                                Placeholder::make('vendor')
                                    ->label('Vendor')
                                    ->content(fn ($record) => $record->product?->vendor->store_name ?? 'N/A'),
                                Toggle::make('options.sent')
                                    ->label('Is Sent')
                                    ->disabled(fn () => Auth::user()->isCustomer()),
                            ])
                            ->deletable(false)
                            ->addable(false)
                            ->reorderable(false),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable(),
                TextColumn::make('user_id')
                    ->label('Customer')
                    ->searchable()
                    ->default(fn ($record) => $record?->user?->profile?->first_name.' '.$record?->user?->profile?->last_name ?? 'N/A'),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->searchable(),
                TextColumn::make('transaction_id')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
