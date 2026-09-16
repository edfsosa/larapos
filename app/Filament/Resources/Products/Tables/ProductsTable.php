<?php

namespace App\Filament\Resources\Products\Tables;

use App\Support\Money;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('barcode')
                    ->label('Código')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')
                    ->label('Precio')
                    ->formatStateUsing(fn($state) => Money::format($state))
                    ->sortable(),
                TextColumn::make('cost')
                    ->label('Costo')
                    ->formatStateUsing(fn($state) => Money::format($state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->color(fn($state) => $state <= 5 ? 'danger' : ($state <= 20 ? 'warning' : 'success'))
                    ->sortable(),
                IconColumn::make('active')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Categoría')
                    ->relationship('category', 'name'),
                TernaryFilter::make('active')
                    ->label('Activo'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
