<?php

namespace App\Filament\Resources\Materials\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RequiredForProductionRelationManager extends RelationManager
{
    protected static string $relationship = 'requiredForProduction';

    protected static ?string $recordTitleAttribute = 'Зарезервовано для виробництва';

    protected static ?string $title = 'Зарезервовано для виробництва';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('production_id')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('unit_cost')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_cost')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('status')
                    ->required()
                    ->default('на складі'),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextEntry::make('production_id')
                //     ->numeric(),
                TextEntry::make('quantity')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, '.', ''))
                    ->label('Кількість'),
                TextEntry::make('unit_cost')
                    ->money('UAH')
                    ->label('Вартість за одиницю'),
                TextEntry::make('total_cost')
                    ->money('UAH')
                    ->label('Загальна вартість'),
                TextEntry::make('status')
                    ->label('Статус'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                Action::make('production_id')
                        ->url(fn ($record) => route('filament.administration.resources.productions.view', ['record' => $record->production_id]))
                        ->label('Перейти до виробництва'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('quantity')
            ->columns([
                TextColumn::make('production.name')
                    ->sortable()
                    ->label('ID виробництва'),
                TextColumn::make('quantity')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, '.', ''))
                    ->sortable()
                    ->label('Кількість'),
                TextColumn::make('unit_cost')
                    ->money('UAH')
                    ->numeric()
                    ->sortable()
                    ->label('Вартість за одиницю'),
                TextColumn::make('total_cost')
                    ->money('UAH')
                    ->label('Загальна вартість'),
                TextColumn::make('status')
                    ->searchable()
                    ->label('Статус'),
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
