<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema, bool $withPassword = false): Schema
    {
        $components = [
            TextInput::make('name')
                ->label('Ім\'я')
                ->required(),
            TextInput::make('email')
                ->label('Електронна пошта')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),
            Select::make('role')
                ->label('Роль')
                ->options(User::roleLabels())
                ->required(),
            DateTimePicker::make('email_verified_at')
                ->label('Пошту підтверджено'),
        ];

        if ($withPassword) {
            $components[] = TextInput::make('password')
                ->label('Пароль')
                ->password()
                ->required()
                ->minLength(8)
                ->dehydrated();
        }

        return $schema->components($components);
    }
}
