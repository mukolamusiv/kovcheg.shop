<?php

namespace App\Filament\Resources\Materials\Schemas;

use Dom\Text;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Picqer\Barcode\Types\TypeEan13;

class MaterialInfolist
{
    public static function configure(Schema $schema): Schema
    {


        //$code = new TypeEan13()->getBarcode($material->barcode);

        return $schema
            ->components([
                Section::make('Основна інформація')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Назва'),
                         TextEntry::make('stock_quantity_for_production')
                            ->label('Зарезервовано'),
                        // TextEntry::make('slug')
                        //     ->label('Слаг'),
                        TextEntry::make('description')
                            ->label('Опис')
                            ->placeholder('-')
                            ->columnSpanFull(),

                    ]),

                Section::make('Категорія та постачальник')
                    ->schema([
                        TextEntry::make('category.name')
                            ->label('Категорія')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('supplier.name')
                            ->label('Постачальник')
                            ->placeholder('-'),
                    ]),

                Section::make('Ціни та кількість')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('cost_per_unit')
                            ->label('Ціна за одиницю')
                            ->money('UAH'),
                        TextEntry::make('stock_quantity')
                            ->label('Кількість на складі'),
                           // ->formatStateUsing(fn ($state) => number_format($state / 100, 2, '.', '')),
                        TextEntry::make('cost_per_all_data')
                            ->label('Вартість всього')
                            ->default(fn ($record) => $record->cost_per_all())
                            ->money('UAH'),
                        TextEntry::make('unit')
                            ->label('Одиниця виміру'),

                    ]),

                Section::make('Додаткова інформація')
                    ->schema([
                        ImageEntry::make('barcode_image')
                            ->label('Штрих-код')
                            ->disk('public'),
                        TextEntry::make('barcode')
                            ->label('Штрих-код')
                            ->placeholder('-'),
                        ImageEntry::make('photo_path')
                            ->label('Шлях до фото')
                            ->placeholder('-'),
                    ]),

                Section::make('Системна інформація')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Створено')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Оновлено')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
