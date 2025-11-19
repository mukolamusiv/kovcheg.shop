<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('account_id')
                    ->required()
                    ->numeric(),
                Select::make('transaction_type')
                    ->options(['витрати' => 'Витрати', 'надходження' => 'Надходження'])
                    ->default('надходження')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('description'),
                DateTimePicker::make('transaction_date'),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('order_id')
                    ->numeric(),
                TextInput::make('manager_id')
                    ->required()
                    ->numeric(),
                TextInput::make('invoice_id')
                    ->numeric(),
            ]);
    }
}
