<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\RepeatableEntry;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Основна інформація')
                    ->columns(2)
                    ->components([
                        TextEntry::make('name')
                            ->label('Назва'),

                        TextEntry::make('slug')
                            ->label('Слаг'),

                        TextEntry::make('description')
                            ->label('Опис')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                     Section::make('Додаткова інформація')
                    ->columns(2)
                    ->components([
                        TextEntry::make('parent.name')
                            ->label('Назва Батьківської Категорії')
                            ->placeholder('-'),

                        TextEntry::make('type')
                            ->label('Тип')
                            ->badge(),

                        // Дата та час
                        TextEntry::make('created_at')
                            ->label('Дата створення')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Дата оновлення')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),


                    Section::make('Дочірні категорії')
                        ->columns(1)
                        ->columnSpanFull()
                        ->headerActions([
                            Action::make('add_category')
                                ->label('Додати категорію')
                                ->form([
                                    \Filament\Forms\Components\TextInput::make('name')
                                        ->label('Назва')
                                        ->required(),

                                    \Filament\Forms\Components\TextInput::make('slug')
                                        ->label('Слаг')
                                        ->required(),

                                    \Filament\Forms\Components\Textarea::make('description')
                                        ->label('Опис')
                                        ->nullable(),

                                    \Filament\Forms\Components\Select::make('parent_id')
                                        ->label('Батьківська категорія')
                                        ->relationship('parent', 'name')
                                        ->nullable(),

                                    \Filament\Forms\Components\Select::make('type')
                                        ->label('Тип')
                                        ->options([
                                            'product' => 'Продукт',
                                            'material' => 'Матеріал',
                                        ])
                                        ->default('material'),
                                ])
                                ->action(function (array $data) {
                                    \App\Models\Category::create($data);
                                }),
                        ])
                        ->components([
                            RepeatableEntry::make('children')
                                    ->label('Дочірні категорії')
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Назва'),
                                        TextEntry::make('description')
                                            ->label('Опис')
                                            ->placeholder('-'),
                                        RepeatableEntry::make('children')
                                            ->label('Підкатегорії')
                                            ->columnSpanFull()
                                            ->schema([
                                                TextEntry::make('name')
                                                    ->label('Назва'),
                                                TextEntry::make('description')
                                                    ->label('Опис')
                                                    ->placeholder('-')
                                            ])
                                ])
                                ->columns(2),
                        ])->columns(1),
            ]);
    }
}
