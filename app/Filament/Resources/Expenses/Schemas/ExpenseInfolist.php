<?php

namespace App\Filament\Resources\Expenses\Schemas;

use App\Models\Expense;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpenseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Загальна інформація')
                    ->schema([
                        TextEntry::make('category')
                            ->label('Категорія')
                            ->formatStateUsing(fn (string $state): string => Expense::CATEGORIES[$state] ?? $state),
                        TextEntry::make('description')
                            ->label('Опис'),
                        TextEntry::make('amount')
                            ->label('Сума')
                            ->numeric()
                            ->money('UAH'),
                        TextEntry::make('expense_date')
                            ->label('Дата')
                            ->date(),
                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'проведено' => 'success',
                                'заплановано' => 'warning',
                                default => 'danger',
                            }),
                        TextEntry::make('user.name')
                            ->label('Отримувач')
                            ->placeholder('—'),
                        TextEntry::make('notes')
                            ->label('Примітки')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
