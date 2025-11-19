<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('account_name')
                    ->required(),
                TextInput::make('account_type')
                    ->required()
                    ->default('готівка'),
                TextInput::make('ipn')
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('UAN'),
                TextInput::make('bank_name'),
                TextInput::make('bank_code'),
                TextInput::make('address'),
                TextInput::make('iban'),
                TextInput::make('account_number'),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
