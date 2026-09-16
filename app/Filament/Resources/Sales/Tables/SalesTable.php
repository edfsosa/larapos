<?php

namespace App\Filament\Resources\Sales\Tables;

use App\Support\Money;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reference')
                    ->label('Folio')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cashier.name')
                    ->label('Cajero')
                    ->searchable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => Money::format($state))
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->label('Pago')
                    ->badge()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'cash' => 'Efectivo',
                        'card' => 'Tarjeta',
                        'mixed' => 'Mixto',
                        default => $state,
                    })
                    ->color(fn($state) => match ($state) {
                        'cash' => 'success',
                        'card' => 'info',
                        'mixed' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_method')
                    ->label('Método de pago')
                    ->options([
                        'cash' => 'Efectivo',
                        'card' => 'Tarjeta',
                        'mixed' => 'Mixto',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
