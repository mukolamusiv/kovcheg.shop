<?php

namespace App\Filament\Resources\Materials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Split::make([

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(),
                TextColumn::make('cost_per_unit')
                    ->label('Ціна за одиницю')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('unit')
                //     ->label('Одиниця виміру')
                //     ->searchable(),
                TextColumn::make('stock_quantity')
                    ->label('Кількість на складі')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_quantity_for_production')
                    ->label('Резерв')
                    ->color(fn ($record) => $record->stock_quantity_for_production > $record->stock_quantity ? 'danger' : 'success'),
                TextColumn::make('supplier_id')
                    ->label('Постачальник')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                   TextColumn::make('category.name')
                    ->label('Категорія')
                    ->sortable()
                    ->searchable(),
                // ImageColumn::make('photo_path')
                //     ->disk('public')
                //     ->label('Фото'),
                ImageColumn::make('photo_path')
                    ->label('Фото'),

                 // Stack::make([
                    ImageColumn::make('barcode_image')
                        ->disk('public')
                        ->label('Штрих-код'),
                    TextColumn::make('barcode')
                        ->label('Штрих-код')
                        ->searchable(),
                // ]),

                        //->imageWidth(200),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // ]),
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
