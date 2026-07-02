<?php

namespace App\Filament\Resources\Salaries\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SalaryForm
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
                TextInput::make('amount')
                    ->label('Сума')
                    ->numeric()
                    ->required()
                    ->minValue(0.01),
                DatePicker::make('salary_date')
                    ->label('Дата')
                    ->default(now())
                    ->required(),
                Select::make('status')
                    ->label('Статус')
                    ->options([
                        'нараховано' => 'Нараховано',
                        'не виплачено' => 'Не виплачено',
                        'виплачено' => 'Виплачено',
                    ])
                    ->default('нараховано')
                    ->required(),
                Textarea::make('notes')
                    ->label('Примітки')
                    ->columnSpanFull(),
            ]);
    }
}
