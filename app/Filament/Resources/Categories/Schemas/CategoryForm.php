<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Назва')
                    ->required(),
                // TextInput::make('slug')
                //     ->required(),
                Textarea::make('description')
                    ->label('Опис')
                    ->columnSpanFull(),
                Select::make('parent_id')
                    ->label('Батьківська категорія')
                    ->relationship('parent', 'name')
                    ->preload()
                    ->searchable(),
                Select::make('type')
                    ->label('Тип')
                    ->options(['product' => 'Product', 'material' => 'Material'])
                    ->default('material')
                    ->required(),
            ]);
    }
}
