<?php

namespace App\Filament\Resources\Orders\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Замовник')
                    ->sortable(),
                // TextColumn::make('total_amount')
                //     ->numeric()
                //     ->label('Загальна сума')
                //     ->sortable(),
                // TextColumn::make('discount_amount')
                //     ->numeric()
                //     ->label('Сума знижки')
                //     ->sortable(),
                // TextColumn::make('paid_amount')
                //     ->numeric()
                //     ->label('Сплачена сума')
                //     ->sortable(),
                TextColumn::make('due_amount')
                    ->numeric()
                    ->label('Сума до сплати')
                    ->sortable(),
                TextColumn::make('deadline')
                    ->date()
                    ->label('Термін виконання')
                    ->sortable(),
                TextColumn::make('delivery')
                    ->label('Спосіб доставки')
                    ->default('Самовивіз')
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус замовлення')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status.payment')
                    ->label('Статус оплати')
                    ->sortable()
                    ->color('danger')
                    ->default('Не сплачено')
                    ->searchable(),
                TextColumn::make('notes')
                    ->label('Примітки')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
