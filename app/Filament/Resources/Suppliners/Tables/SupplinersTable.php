<?php

namespace App\Filament\Resources\Suppliners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SupplinersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Ім\'я')->searchable()->sortable(),
                TextColumn::make('email')->label('Електронна пошта')->searchable()->sortable(),
                TextColumn::make('role')->label('Роль')->badge()->sortable(),
                TextColumn::make('created_at')->label('Створено')->dateTime()->sortable(),
                TextColumn::make('customer.phone')->label('Телефон'),
                //TextColumn::make('email_verified_at')->label('Підтверджено електронну пошту')->dateTime()->sortable(),
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
