<?php

namespace App\Filament\Resources\Accounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('account_name')
                    ->label('Назва рахунку')
                    ->searchable(),
                TextColumn::make('account_type')
                    ->label('Тип рахунку')
                    ->searchable(),
                TextColumn::make('balance')
                    ->label('Баланс')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('currency')
                    ->label('Валюта')
                    ->searchable(),
                TextColumn::make('ipn')
                    ->label('ІПН')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bank_name')
                    ->label('Назва банку')
                    ->searchable(),
                TextColumn::make('bank_code')
                    ->label('Код банку')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('Адреса')
                    ->searchable(),
                TextColumn::make('iban')
                    ->label('IBAN')
                    ->searchable(),
                TextColumn::make('account_number')
                    ->label('Номер рахунку')
                    ->searchable(),
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
