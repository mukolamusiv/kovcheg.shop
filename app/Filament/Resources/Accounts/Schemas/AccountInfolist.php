<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AccountInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Деталі акаунту')
                    ->schema([
                        TextEntry::make('account_name')
                            ->label('Назва акаунту'),
                        TextEntry::make('account_type')
                            ->label('Тип акаунту'),
                        TextEntry::make('currency')
                            ->label('Валюта'),
                        TextEntry::make('balance')
                            ->numeric()
                            ->label('Баланс'),
                    ]),
                Section::make('Інформація про банк')
                    ->schema([
                        TextEntry::make('bank_name')
                            ->placeholder('-')
                            ->label('Назва банку'),
                        TextEntry::make('bank_code')
                            ->placeholder('-')
                            ->label('Код банку'),
                        TextEntry::make('iban')
                            ->placeholder('-')
                            ->label('IBAN'),
                        TextEntry::make('account_number')
                            ->placeholder('-')
                            ->label('Номер рахунку'),
                    ]),
                Section::make('Контактна інформація')
                    ->schema([
                        TextEntry::make('ipn')
                            ->numeric()
                            ->placeholder('-')
                            ->label('ІПН'),
                        TextEntry::make('address')
                            ->placeholder('-')
                            ->label('Адреса'),
                    ]),
                Section::make('Позначки часу')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-')
                            ->label('Створено'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-')
                            ->label('Оновлено'),
                    ]),
            ]);
    }
}
