<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CostsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('account.name')
                    ->label('Рахунок')
                    ->relationship('account', 'name')
                    ->required(),
                // Select::make('transaction_type')
                //     ->options(['витрати' => 'Витрати', 'надходження' => 'Надходження'])
                //     ->default('надходження')
                //     ->required(),
                TextInput::make('amount')
                    ->required()
                    ->label('Сума')
                    ->numeric(),
                Textarea::make('description'),
                DateTimePicker::make('transaction_date')->default(now())->label('Дата транзакції')->required(),
                // TextInput::make('user_id')
                //     ->required()
                //     ->numeric(),
                // TextInput::make('order_id')
                //     ->numeric(),
                TextInput::make('manager_id')
                    ->required()
                    ->hidden()
                    ->default(auth()->id())
                    ->numeric(),
                // TextInput::make('invoice_id')
                //     ->numeric(),
            ]);
    }
}
