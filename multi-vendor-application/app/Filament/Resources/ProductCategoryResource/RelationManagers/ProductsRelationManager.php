<?php

namespace App\Filament\Resources\ProductCategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        $user = Auth::user();

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('vendor_id')
                    ->relationship('vendor', 'store_name')
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->preload()
                    ->hidden(fn () => $user->isVendor())
                    ->default(fn () => $user->isVendor() ? $user->vendor->id : null)
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
                    ->label('Category')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(fn () => $this->ownerRecord->id)
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('image')
                    ->label('Image'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('sale_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->sortable(),
                TextColumn::make('discount')
                    ->money()
                    ->sortable(),
                TextColumn::make('vendor.store_name')
                    ->label('Vendor')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
