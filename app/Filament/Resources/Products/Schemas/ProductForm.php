<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('barcode')
                    ->label('Código de barras')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('price')
                    ->label('Precio')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->prefix('Gs.'),
                TextInput::make('cost')
                    ->label('Costo')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->default(0)
                    ->prefix('Gs.'),
                TextInput::make('stock')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->default(0),
                Toggle::make('active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}
