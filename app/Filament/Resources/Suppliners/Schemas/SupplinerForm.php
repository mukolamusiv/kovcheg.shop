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
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Ім\'я')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('email')
                            ->label('Електронна пошта')
                            ->unique(ignoreRecord: true)
                            ->email(),
                        \Filament\Forms\Components\Select::make('role')
                            ->label('Роль')
                            ->options([
                                'admin' => 'Адміністратор',
                                'manager' => 'Менеджер',
                                'employee' => 'Працівник',
                                'supplier' => 'Постачальник',
                                'customer' => 'Клієнт',
                            ])
                            ->default('supplier')
                            ->required(),
                    ]),
                Section::make('Додаткова інформація')
                    ->schema([
                        // \Filament\Forms\Components\TextInput::make('user_id')
                        //     ->label('Користувач')
                        //     ->required()
                        //     ->numeric(),
                        \Filament\Forms\Components\TextInput::make('customer.phone')
                            ->label('Телефон')
                            ->tel()
                            ->nullable(),
                        \Filament\Forms\Components\TextInput::make('customer.address')
                            ->label('Адреса')
                            ->nullable(),
                    ]),
                Section::make('Особисті дані')
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('customer.date_of_birth')
                            ->label('Дата народження')
                            ->nullable(),
                        \Filament\Forms\Components\TextInput::make('customer.city')
                            ->label('Місто')
                            ->nullable(),
                        \Filament\Forms\Components\Textarea::make('customer.note')
                            ->label('Примітка')
                            ->nullable(),
                    ]),
                // Section::make('Вимірювання')
                //     ->schema([
                //         \Filament\Forms\Components\KeyValue::make('measurements')
                //             ->label('Вимірювання')
                //             ->nullable(),
                //     ]),
            ]);
    }
}
