<?php

namespace App\Filament\Resources\Coupons\Tables;

use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('type')
                    ->colors([
                        'fixed' => 'success',
                        'percentage' => 'info'
                    ])
                    ->badge(),
                TextColumn::make('value')
                    ->label('Discount')
                    ->formatStateUsing(
                        fn($record) => $record->type === "percentage" ? $record->value . '%' : '$' . number_format($record->value, 2)
                    )
                    ->weight('bold')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('minimum_order_value')
                    ->label('Min. Orders')
                    ->money('USD')
                    ->sortable(), 
                TextColumn::make('usage_limit')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('usage_count')
                    ->counts('usages')
                    ->label('Used')
                    ->color('warning')  
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->placeholder('Active Now')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($state) => filled($state) && Carbon::parse($state)->isPast() ? 'danger' : 'gray'),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'DESC') 
            ->filters([
                SelectFilter::make('type')
                    ->native(false)
                    ->options([
                        'fixed' => 'Fixed',
                        'percentage' => 'Percentage'
                    ]),
                    TernaryFilter::make('is_active')
                        ->label('Status')
                        ->boolean()
                        ->trueLabel('Active Only')
                        ->falseLabel('Inactive Only')
                        ->native(false)
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
