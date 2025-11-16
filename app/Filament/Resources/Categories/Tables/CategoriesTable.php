<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(),
                // TextColumn::make('slug')
                //     ->searchable(),
                TextColumn::make('parent.name')
                    ->label('Батьківська категорія')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('children.name')
                    ->listWithLineBreaks()
                    ->label('Підкатегорії'),
                // TextColumn::make('type')
                //     ->badge(),
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
            // ->groups([
            //     // групуємо по parent_id — без self-join
            //     Group::make('parent_name')
            //         ->label('Батьківська категорія')
            //         ->collapsible() // дозволяє згорнути/розгорнути
            //         //->getTitleFromRecordUsing(fn ($record) => $record->parent?->name ?? 'Без батьківської категорії'),
            // ])
            //->defaultGroup('parent.name')
            //->defaultSort('name')
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


    // protected function getTableQuery(): Builder
    // {
    //     return Category::query()
    //         ->leftJoin('categories as parents', 'categories.parent_id', '=', 'parents.id')
    //         ->select('categories.*', 'parents.name as parent_name');
    // }

    // protected static function getTableQuery(): Builder
    // {
    //     return Category::query()->with('parent');
    // }
}
