<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('account_name')
                    ->label('Назва рахунку')
                    ->required(),
                Select::make('account_type')
                    ->label('Тип рахунку')
                    ->required()
                    ->options([
                        'готівка' => 'Готівка',
                        'картка' => 'Картка',
                        'рахунок' => 'Рахунок',
                    ])
                    ->default('готівка'),
                TextInput::make('ipn')
                    ->label('ІПН')
                    ->numeric(),
                Select::make('currency')
                    ->label('Валюта')
                    ->required()
                    ->options([
                        'USD' => 'Долар США',
                        'EUR' => 'Євро',
                        'UAN' => 'Українська гривня',
                    ])
                    ->default('UAN'),
                TextInput::make('bank_name')
                    ->label('Назва банку'),
                TextInput::make('bank_code')
                    ->label('Код банку'),
                TextInput::make('address')
                    ->label('Адреса'),
                TextInput::make('iban')
                    ->label('IBAN'),
                TextInput::make('account_number')
                    ->label('Номер рахунку'),
                TextInput::make('balance')
                    ->label('Баланс')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
