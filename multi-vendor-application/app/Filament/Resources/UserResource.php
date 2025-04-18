<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Rawilk\FilamentPasswordInput\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Admin Management';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Info')
                    ->schema([
                        TextInput::make('email')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->email(),

                        Password::make('password')
                            ->required(fn ($context) => $context === 'create')
                            ->maxLength(255)
                            ->dehydrated(fn ($state) => ! empty($state) ? Hash::make($state) : null)
                            ->hint('Leave blank to keep the current password')
                            ->visible(fn ($context) => $context === 'create'),

                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'customer' => 'Customer',
                                'vendor' => 'Vendor',
                            ])
                            ->native(false)
                            ->searchable()
                            ->required()
                            ->default('customer')
                            ->live(),

                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->default(true),
                    ])
                    ->columns(2),

                Section::make('Profile Info')
                    ->relationship('profile')
                    ->schema([
                        TextInput::make('first_name')
                            ->label('First Name'),
                        TextInput::make('last_name')
                            ->label('Last Name'),
                        TextInput::make('phone_number')
                            ->label('Phone Number'),
                        TextInput::make('address')
                            ->label('Address'),
                    ])
                    ->columns(2),

                Section::make('Vendor Info')
                    ->relationship('vendor')
                    ->schema([
                        TextInput::make('store_name')
                            ->label('Store Name')
                            ->required(fn ($get) => $get('role') !== 'vendor'),
                        TextArea::make('description')
                            ->label('Description')
                            ->required(fn ($get) => $get('role') !== 'vendor'),
                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->default(true)
                            ->required(fn ($get) => $get('role') !== 'vendor'),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('role') === 'vendor'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('profile.first_name')
                    ->label('First Name')
                    ->searchable(),
                TextColumn::make('profile.last_name')
                    ->label('Last Name')
                    ->searchable(),
                TextColumn::make('profile.phone_number')
                    ->label('Phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->state(function ($record) {
                        return $record->is_active ? 'Active' : 'Inactive';
                    })
                    ->color(function ($record) {
                        return $record->is_active ? 'success' : 'danger';
                    }),
                TextColumn::make('last_login')
                    ->label('Last Login'),
                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->state(function ($record) {
                        return match ($record->role) {
                            'admin' => 'Admin',
                            'customer' => 'Customer',
                            'vendor' => 'Vendor',
                            default => 'Unknown'
                        };
                    })
                    ->color(function ($record) {
                        return match ($record->role) {
                            'admin' => 'gray',
                            'customer' => 'primary',
                            'vendor' => 'success',
                        };
                    }),
                TextColumn::make('created_at')
                    ->label('Registered At')
                    ->date('Y-m-d')
                    ->searchable(),
            ])
            ->filters([

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
