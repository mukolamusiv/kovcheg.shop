<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('account.account_name')
                    ->searchable()
                    ->label('Рахунок')
                    ->sortable(),
                TextColumn::make('transaction_type')
                    ->label('Тип переказу')
                    ->badge(),
                TextColumn::make('amount')
                    ->label('Сума')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Призначення')
                    ->searchable(),
                TextColumn::make('transaction_date')
                    ->label('Дата')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Платник/отримувач')
                    ->sortable(),
                TextColumn::make('order_id')
                    ->label('Замовлення')
                    ->sortable(),
                TextColumn::make('manager.name')
                    ->label('Менеджер')
                    ->sortable(),
                TextColumn::make('invoice_id')
                    ->numeric()
                    ->label('Накладна')
                    ->sortable(),
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
