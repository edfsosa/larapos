<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric(),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total')
                    ->required()
                    ->numeric(),
                Select::make('payment_method')
                    ->options(['cash' => 'Cash', 'card' => 'Card', 'mixed' => 'Mixed'])
                    ->required(),
                TextInput::make('amount_received')
                    ->numeric(),
                TextInput::make('change')
                    ->numeric(),
                TextInput::make('reference')
                    ->required(),
            ]);
    }
}
