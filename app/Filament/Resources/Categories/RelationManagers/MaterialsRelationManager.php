<?php

namespace App\Filament\Resources\Categories\RelationManagers;

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
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Назва')
                    ->required(),
                // TextInput::make('slug')
                //     ->label('URL')
                //     ->required(),
                // Textarea::make('description')
                //     ->label('Опис')
                //     ->columnSpanFull(),
                TextInput::make('zip_code')
                    ->label('Штрихкод')
                    ->numeric()
                    ->unique()
                    ->nullable(),
                TextInput::make('cost_per_unit')
                    ->label('Вартість за одиницю')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('unit')
                    ->label('Одиниця вимірювання')
                    ->required()
                    ->default('метри погонні'),
                TextInput::make('stock_quantity')
                    ->label('Кількість на складі')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('supplier_id')
                    ->label('Постачальник')
                    ->relationship('supplier', 'name')
                    ->preload()
                    ->searchable()
                    ->nullable(),
                TextInput::make('photo_path')
                    ->label('Шлях до фото')
                    ->nullable(),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Назва'),
                TextEntry::make('slug')
                    ->label('URL'),
                TextEntry::make('description')
                    ->label('Опис')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('zip_code')
                    ->label('Штрихкод')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('cost_per_unit')
                    ->label('Вартість за одиницю')
                    ->numeric(),
                TextEntry::make('unit')
                    ->label('Одиниця вимірювання'),
                TextEntry::make('stock_quantity')
                    ->label('Кількість на складі')
                    ->numeric(),
                TextEntry::make('supplier_id')
                    ->label('Постачальник')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('photo_path')
                    ->label('Шлях до фото')
                    ->placeholder('-'),
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
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(),
                TextColumn::make('zip_code')
                    ->label('Штрихкод')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('slug')
                //     ->label('URL')
                //     ->searchable(),
                TextColumn::make('description')
                    ->label('Опис')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('cost_per_unit')
                    ->label('Вартість за одиницю')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('unit')
                //     ->label('Одиниця вимірювання')
                //     ->searchable(),
                TextColumn::make('stock_quantity')
                    ->label('Кількість на складі')
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('supplier.name')
                //     ->label('Постачальник')
                //     ->searchable(),
                TextColumn::make('photo_path')
                    ->label('Шлях до фото')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Оновлено')
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
