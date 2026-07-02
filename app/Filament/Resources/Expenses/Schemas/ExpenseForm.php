<?php

namespace App\Filament\Resources\Expenses\Schemas;

use App\Models\Expense;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExpenseForm
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
                Select::make('status')
                    ->label('Статус')
                    ->options([
                        'заплановано' => 'Заплановано',
                        'проведено' => 'Проведено',
                        'скасовано' => 'Скасовано',
                    ])
                    ->default('заплановано')
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
