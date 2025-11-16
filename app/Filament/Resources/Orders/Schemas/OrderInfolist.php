<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer.name')
                    ->label('Клієнт')
                    ->placeholder('-'),
                TextEntry::make('total_amount')
                    ->label('Загальна сума')
                    ->numeric(),
                TextEntry::make('discount_amount')
                    ->label('Сума знижки')
                    ->numeric(),
                TextEntry::make('paid_amount')
                    ->label('Сплачена сума')
                    ->numeric(),
                TextEntry::make('due_amount')
                    ->label('Заборгованість')
                    ->numeric(),
                TextEntry::make('deadline')
                    ->label('Термін')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->label('Примітки')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->label('Статус'),
                TextEntry::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
