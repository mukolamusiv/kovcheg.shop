<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'OrderItems';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Позиції замовлення';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product.name')
                    ->label('Продукт')
                    ->relationship('product', 'name'),
                Select::make('production_id')
                    ->label('Виробництво')
                    ->relationship('production', 'id'),
                TextInput::make('quantity')
                    ->required()
                    ->label('Кількість')
                    ->numeric()
                    ->default(1),
                TextInput::make('unit_price')
                    ->required()
                    ->label('Ціна за одиницю')
                    ->numeric()
                    ->default(0),
                TextInput::make('total')
                    ->required()
                    ->label('Загальна сума')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('product.name')
                    ->label('Продукт')
                    ->sortable(),
                TextColumn::make('production.name')
                    ->label('Виробництво')
                    ->sortable(),
                TextColumn::make('production.status')
                    ->label('Статус виробництва')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Кількість')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('Ціна за одиницю')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Загальна сума')
                    ->numeric()
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
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
