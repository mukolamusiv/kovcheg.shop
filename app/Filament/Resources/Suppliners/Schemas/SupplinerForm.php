<?php

namespace App\Filament\Resources\Suppliners\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupplinerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Інформація користувача')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('user_id')
                            ->label('Користувач')
                            ->required()
                            ->numeric(),
                        \Filament\Forms\Components\TextInput::make('phone')
                            ->label('Телефон')
                            ->tel()
                            ->nullable(),
                        \Filament\Forms\Components\TextInput::make('address')
                            ->label('Адреса')
                            ->nullable(),
                    ]),
                Section::make('Особисті дані')
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Дата народження')
                            ->nullable(),
                        \Filament\Forms\Components\TextInput::make('city')
                            ->label('Місто')
                            ->nullable(),
                        \Filament\Forms\Components\Textarea::make('note')
                            ->label('Примітка')
                            ->nullable(),
                    ]),
                Section::make('Вимірювання')
                    ->schema([
                        \Filament\Forms\Components\KeyValue::make('measurements')
                            ->label('Вимірювання')
                            ->nullable(),
                    ]),
            ]);
    }
}
