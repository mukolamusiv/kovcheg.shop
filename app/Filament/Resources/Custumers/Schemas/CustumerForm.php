<?php

namespace App\Filament\Resources\Custumers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section;

class CustumerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Ім\'я')
                    ->required(),
                TextInput::make('email')
                    ->label('Електронна пошта')
                    ->email(),
                Select::make('role')
                    ->label('Роль')
                    ->options([
                        'admin' => 'Адміністратор',
                        'manager' => 'Менеджер',
                        'employee' => 'Працівник',
                        'supplier' => 'Постачальник',
                        'customer' => 'Клієнт',
                    ])
                    ->default('customer')
                    ->required(),

                    Section::make('Детальна інформація клієнта')
                        ->relationship('customer') // <<< Ось це головне!
                        ->columnSpanFull()
                        ->schema([
                            TextInput::make('phone')
                            ->label('Телефон')
                            ->nullable(),
                        TextInput::make('address')
                            ->label('Адреса')
                            ->nullable(),
                        DatePicker::make('date_of_birth')
                            ->label('Дата народження')
                            ->nullable(),
                        TextInput::make('city')
                            ->label('Місто')
                            ->nullable(),
                        Textarea::make('note')
                            ->label('Примітка')
                            ->nullable(),
                        KeyValue::make('measurements')
                            ->label('Розміри'),
                        ])
            ]);
    }
}
