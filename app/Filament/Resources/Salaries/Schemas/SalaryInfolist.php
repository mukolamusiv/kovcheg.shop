<?php

namespace App\Filament\Resources\Salaries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SalaryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Загальна інформація')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Працівник'),
                        TextEntry::make('amount')
                            ->label('Сума')
                            ->numeric()
                            ->money('UAH'),
                        TextEntry::make('salary_date')
                            ->label('Дата')
                            ->date(),
                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'виплачено' => 'success',
                                'нараховано' => 'warning',
                                default => 'danger',
                            }),
                        TextEntry::make('notes')
                            ->label('Примітки')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
