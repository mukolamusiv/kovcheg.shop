<?php

namespace App\Filament\Resources\Custumers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class CustumersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')->label('Ім\'я')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('email')->label('Електронна пошта')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('phone')->label('Телефон')->sortable()->searchable(),
                \Filament\Tables\Columns\TextColumn::make('address')->label('Адреса')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('date_of_birth')->label('Дата народження')->date()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('city')->label('Місто')->sortable(),
                \Filament\Tables\Columns\TextColumn::make('note')->label('Примітка')->limit(50),
                \Filament\Tables\Columns\TextColumn::make('created_at')->label('Створено')->dateTime()->sortable(),
                \Filament\Tables\Columns\TextColumn::make('updated_at')->label('Оновлено')->dateTime()->sortable(),
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
