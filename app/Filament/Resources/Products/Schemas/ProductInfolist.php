<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Support\Money;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')->label('Nombre'),
                TextEntry::make('category.name')->label('Categoría')->placeholder('-'),
                TextEntry::make('sku')->label('SKU'),
                TextEntry::make('barcode')->label('Código de barras')->placeholder('-'),
                TextEntry::make('price')
                    ->label('Precio')
                    ->formatStateUsing(fn($state) => Money::format($state)),
                TextEntry::make('cost')
                    ->label('Costo')
                    ->formatStateUsing(fn($state) => Money::format($state)),
                TextEntry::make('stock')->label('Stock'),
                IconEntry::make('active')->label('Activo')->boolean(),
                TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
