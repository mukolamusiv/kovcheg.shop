<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Деталі транзакції')
                    ->schema([
                        TextEntry::make('account.account_name')
                            ->label('ID акаунта')
                            ->numeric(),
                        TextEntry::make('transaction_type')
                            ->label('Тип транзакції')
                            ->badge(),
                        TextEntry::make('amount')
                            ->label('Сума')
                            ->money('UAN'),
                        TextEntry::make('description')
                            ->label('Опис')
                            ->placeholder('-'),
                        TextEntry::make('transaction_date')
                            ->label('Дата транзакції')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),

                Section::make('Інформація про користувача та замовлення')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Клієнт/постачальник'),
                        TextEntry::make('order_id')
                            ->label('ID замовлення')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('manager.name')
                            ->label('Провів транзакцію'),
                    ]),

                Section::make('Рахунок та часові мітки')
                    ->schema([
                        TextEntry::make('invoice_id')
                            ->label('ID рахунку')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label('Створено')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Оновлено')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
