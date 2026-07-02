<?php

namespace App\Filament\Resources\Expenses\Schemas;

use App\Models\Account;
use App\Models\Expense;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AddExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category')
                    ->label('Категорія')
                    ->options(Expense::CATEGORIES)
                    ->searchable()
                    ->required(),
                Select::make('account_id')
                    ->label('Рахунок')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(fn () => Account::pluck('account_name', 'id')),
                TextInput::make('amount')
                    ->label('Сума')
                    ->numeric()
                    ->required()
                    ->minValue(0.01),
                TextInput::make('description')
                    ->label('Опис')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('expense_date')
                    ->label('Дата')
                    ->default(now())
                    ->required(),
                Select::make('user_id')
                    ->label('Отримувач')
                    ->searchable()
                    ->preload()
                    ->options(fn () => User::whereIn('role', ['employee', 'manager', 'admin', 'supplier'])
                        ->pluck('name', 'id')),
                Textarea::make('notes')
                    ->label('Примітки')
                    ->columnSpanFull(),
            ]);
    }
}
