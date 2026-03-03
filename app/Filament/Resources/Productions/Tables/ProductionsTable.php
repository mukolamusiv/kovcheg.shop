<?php

namespace App\Filament\Resources\Productions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(),
                TextColumn::make('cost_price')
                    ->label('Собівартість')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_cost')
                    ->label('Загальна вартість')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_template')
                    ->label('Шаблон')
                    ->boolean(),
                TextColumn::make('order_id')
                    ->label('ID замовлення')
                    ->numeric()
                    ->url(fn ($record) => route('filament.administration.resources.orders.edit', $record->order_id))
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Продукт')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Статус')
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
            ->defaultSort('created_at', 'desc')
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
