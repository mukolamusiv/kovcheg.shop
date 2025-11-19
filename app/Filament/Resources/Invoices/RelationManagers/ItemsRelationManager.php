<?php

namespace App\Filament\Resources\Invoices\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('material_id')
                    ->required()
                    ->relationship('material', 'name')
                    ->preload()
                    ->searchable()
                    ->label('Матеріал')
                    ->afterStateUpdated(function (callable $set, $state) {
                        $material = \App\Models\Material::find($state);
                        $set('price_per_unit', $material?->cost_per_unit ?? 0);
                    }),
                TextInput::make('quantity')
                    ->required()
                    ->label('Кількість')
                    ->numeric()
                    ->default(1),
                TextInput::make('price_per_unit')
                    ->required()
                    ->label('Ціна за одиницю')
                    ->numeric()
                    ->default(fn ($record) => $record?->material?->cost_per_unit ?? 0),
                // TextInput::make('total_price')
                //     ->required()
                //     ->numeric()
                //     ->default(0),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('material.name')
                    ->label('Матеріал'),
                TextEntry::make('quantity')
                    ->label('Кількість')
                    ->numeric(),
                TextEntry::make('price_per_unit')
                    ->label('Ціна за одиницю')
                    ->numeric(),
                TextEntry::make('total_price')
                    ->label('Загальна ціна')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('material')
            ->columns([
                TextColumn::make('material.name')
                    ->label('Матеріал')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Кількість')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price_per_unit')
                    ->label('Ціна за одиницю')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_price')
                    ->label('Загальна ціна')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('movent_date')
                    ->label('Дата переміщення на склад')
                    ->date()
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
                ViewAction::make(),
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
