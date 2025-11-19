<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Загальна інформація')
                    ->schema([
                        TextEntry::make('invoice_number')
                            ->label('Номер рахунку'),
                        TextEntry::make('invoice_date')
                            ->label('Дата рахунку')
                            ->date(),
                        TextEntry::make('status')
                            ->label('Статус'),
                    ]),

                Section::make('Фінансова інформація')
                    ->schema([
                        TextEntry::make('total_amount')
                            ->label('Загальна сума')
                            ->numeric()
                            ->money('UAN'),
                            TextEntry::make('paid_for_amount')
                            ->label('Оплачено')
                            ->default(fn ($record) => $record->paid_for())
                            ->money('UAN'),
                    ]),

                Section::make('Інформація про постачальника')
                    ->schema([
                        TextEntry::make('supplier.name')
                            ->label('Ім\'я постачальника'),

                    ]),

                Section::make('Оплати та транзакції')
                    ->schema([
                        \Filament\Infolists\Components\RepeatableEntry::make('transactions')
                            ->label('Транзакції')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('transaction_date')
                                    ->label('Дата транзакції'),
                                TextEntry::make('transaction_type')
                                    ->label('Тип транзакції'),
                                TextEntry::make('amount')
                                    ->label('Сума')
                                    ->money('UAN'),
                                TextEntry::make('description')
                                    ->label('Опис'),
                            ])
                    ]),

                Section::make('Товари')
                    ->schema([
                        TextEntry::make('items')
                            ->label('Товари рахунку')
                            ->formatStateUsing(function ($state, $record) {
                                return $record->items->map(function ($item) {
                                    return "{$item->material->name} (x{$item->quantity}): {$item->total_price} центів";
                                })->join(', ');
                            }),
                    ]),
            ]);
    }
}
