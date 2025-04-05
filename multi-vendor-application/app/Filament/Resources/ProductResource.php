<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use function number_format;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Admin Managment';

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if ($user->isVendor()) {
                return $user->vendor->products()->count();
        }

        return static::getModel()::count();
    }

    public static function  canViewAny(): bool
    {
        $user = Auth::user();
        if($user->isAdmin() || $user->isVendor ()){
            return true;
        }
        return false;
    }

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('vendor_id')
                                       ->relationship('vendor', 'store_name')
                                       ->native (false)
                                       ->searchable ()
                                       ->required()
                                       ->preload ()
                                       ->hidden (fn () => $user->isVendor())
                                       ->default(fn() => $user->isVendor() ? $user->vendor->id : null)
                                       ->disabled(fn () => $user->isVendor()),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->dehydrateStateUsing(fn ($state) => number_format((float) $state, 2, '.', ''))
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, '.', ''))
                    ->prefix('$'),
                Forms\Components\TextInput::make('sale_price')
                    ->numeric()
                    ->prefix('$')
                    ->dehydrateStateUsing(fn ($state) => number_format((float) $state, 2, '.', ''))
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, '.', '')),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->dehydrateStateUsing(fn ($state) => number_format((float) $state, 2, '.', ''))
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, '.', '')),
                Forms\Components\FileUpload::make('image')
                    ->image(),
                Forms\Components\Select::make('product_category_id')
                    ->relationship('category', 'name')
                    ->label ('Category')
                    ->native (false)
                    ->searchable ()
                    ->preload ()
                    ->required (),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vendor.store_name')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
