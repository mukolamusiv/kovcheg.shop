<?php

namespace App\Filament\Resources\Expenses\Tables;

use App\Models\Expense;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->label('Категорія')
                    ->formatStateUsing(fn (string $state): string => Expense::CATEGORIES[$state] ?? $state)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Опис')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('amount')
                    ->label('Сума')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expense_date')
                    ->label('Дата')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'проведено' => 'success',
                        'заплановано' => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('user.name')
                    ->label('Отримувач')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('expense_date', 'desc')
            ->filters([
                SelectFilter::make('category')
                    ->label('Категорія')
                    ->options(Expense::CATEGORIES),
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'заплановано' => 'Заплановано',
                        'проведено' => 'Проведено',
                        'скасовано' => 'Скасовано',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
