<?php

namespace App\Filament\Resources\Salaries\Schemas;

use App\Models\Account;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaySalaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Працівник')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(fn () => User::whereIn('role', ['employee', 'manager', 'admin'])
                        ->pluck('name', 'id')),
                Select::make('account_id')
                    ->label('Рахунок')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(fn () => Account::pluck('account_name', 'id')),
                TextInput::make('amount')
                    ->label('Сума виплати')
                    ->numeric()
                    ->required()
                    ->minValue(0.01),
                DatePicker::make('salary_date')
                    ->label('Дата виплати')
                    ->default(now())
                    ->required(),
                Textarea::make('notes')
                    ->label('Примітки')
                    ->columnSpanFull(),
            ]);
    }
}
