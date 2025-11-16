<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Назва')
                    ->required(),
                // TextInput::make('slug')
                //     ->label('Слаг')
                //     ->hidden(),
                Textarea::make('description')
                    ->label('Опис')
                    ->columnSpanFull(),
                Select::make('category.name')
                    ->relationship('category', 'name')
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Назва')
                            ->required(),
                    ])
                    ->label('Категорія'),
                TextInput::make('cost_per_unit')
                    ->label('Ціна за одиницю')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('unit')
                    ->label('Одиниця виміру')
                    ->options([
                        'метри погонні' => 'метри погонні',
                        'кілограми' => 'кілограми',
                        'штуки' => 'штуки',
                        'літри' => 'літри',
                    ])
                    ->required()
                    ->default('метри погонні'),
                TextInput::make('stock_quantity')
                    ->label('Кількість на складі')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('supplier.name')
                    ->relationship('supplier', 'name', fn ($query) => $query->where('role', 'supplier'))
                    ->label('Постачальник'),
                FileUpload::make('photo_path')
                    ->label('Фото'),
            ]);
    }
}
