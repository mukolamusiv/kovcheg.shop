<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\User;
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
                Select::make('account_id')
                    ->label('Рахунок')
                    ->relationship('account', 'account_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('amount')
                    ->label('Сума')
                    ->numeric()
                    ->required()
                    ->minValue(0.01),
                Textarea::make('description')
                    ->label('Опис витрати')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('transaction_date')
                    ->label('Дата')
                    ->default(now())
                    ->required(),
                Select::make('user_id')
                    ->label('Отримувач')
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->id())
                    ->options(fn () => User::whereIn('role', ['employee', 'manager', 'admin', 'supplier'])
                        ->pluck('name', 'id')),
            ]);
    }
}
