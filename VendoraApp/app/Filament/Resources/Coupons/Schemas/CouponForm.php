<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Coupon Information')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, $set) => $set('code', strtoupper($state)))
                            ->required(),
                        Select::make('type')
                            ->options(['fixed' => 'Fixed', 'percentage' => 'Percentage'])
                            ->default('percentage')
                            ->required()
                            ->live(),
                        TextInput::make('value')
                            ->numeric()
                            ->minValue(0)
                            ->prefix(fn (Get $get): string => ($get('type') ?? 'percentage') === 'fixed' ? '$' : '')
                            ->suffix(fn (Get $get): string => ($get('type') ?? 'percentage') === 'percentage' ? '%' : '')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Condotions')
                            ->required(),
                    ]),
                Section::make('Condotions & Limits')
                    ->schema([
                        TextInput::make('minimum_order_value')
                            ->prefix('$')
                            ->minValue(0)
                            ->numeric()
                            ->default(null),
                        TextInput::make('maximum_discount')
                            ->prefix('$')
                            ->minValue(0)
                            ->visible(fn($get) => $get('type') === 'fixed')
                            ->default(null)
                            ->numeric(),
                        TextInput::make('usage_limit')
                            ->minValue(1)
                            ->numeric()
                            ->default(null),
                        TextInput::make('usage_limit_per_customer')
                            ->minValue(1)
                            ->numeric()
                            ->default(null),
                    ]),
                Section::make('Validity Period')
                    ->schema([
                        DateTimePicker::make('starts_at')
                        ->native(false)
                        ->helperText('The coupon will be active from this date and time.'),
                        DateTimePicker::make('expires_at')
                            ->native(false)
                    ]),
            ]);
    }
}
