<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Support\Money;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SaleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference')->label('Folio'),
                TextEntry::make('cashier.name')->label('Cajero'),
                TextEntry::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i'),
                TextEntry::make('payment_method')
                    ->label('Método de pago')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'cash' => 'Efectivo',
                        'card' => 'Tarjeta',
                        'mixed' => 'Mixto',
                        default => $state,
                    }),
                TextEntry::make('subtotal')
                    ->label('Subtotal')
                    ->formatStateUsing(fn($state) => Money::format($state)),
                TextEntry::make('tax')
                    ->label('IVA (10%)')
                    ->formatStateUsing(fn($state) => Money::format($state)),
                TextEntry::make('total')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => Money::format($state))
                    ->weight('bold')
                    ->size('lg'),
                TextEntry::make('amount_received')
                    ->label('Recibido')
                    ->formatStateUsing(fn($state) => $state ? Money::format($state) : '-'),
                TextEntry::make('change')
                    ->label('Cambio')
                    ->formatStateUsing(fn($state) => $state !== null ? Money::format($state) : '-'),

                RepeatableEntry::make('items')
                    ->label('Productos')
                    ->schema([
                        TextEntry::make('product.name')->label('Producto'),
                        TextEntry::make('quantity')->label('Cantidad'),
                        TextEntry::make('unit_price')
                            ->label('P. Unitario')
                            ->formatStateUsing(fn($state) => Money::format($state)),
                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->formatStateUsing(fn($state) => Money::format($state)),
                    ])
                    ->columns(4),
            ]);
    }
}
