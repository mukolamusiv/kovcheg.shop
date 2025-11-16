<?php

namespace App\Filament\Resources\Productions\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use League\CommonMark\Util\HtmlElement;

class ProductionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Не вистачає матеріалів')
                    ->columns(2)
                    ->headerActions([
                        Action::make('order_materials')
                            ->label('Замовити матеріали')
                            ->icon('heroicon-o-shopping-cart')
                            ->form([
                                Select::make('material_id')
                                    ->label('Матеріал')
                                    ->options(function () {
                                        // Повертає список матеріалів для вибору
                                        return \App\Models\Material::pluck('name', 'id');
                                    })
                                    ->required(),
                                TextInput::make('expected_delivery_date')
                                    ->label('Очікувана дата доставки'),
                            ])
                            ->action(function (array $data, \Filament\Resources\Pages\Page $livewire): void {
                                $record = $livewire->record;
                                // Логіка для замовлення матеріалів
                                Notification::make()
                                    ->title('Замовлення матеріалів створено.')
                                    ->success()
                                    ->send();
                            }),
                    ])
                    ->columnSpanFull()
                    ->schema([
                        RepeatableEntry::make('missing_materials')
                            ->label('Відсутні матеріали')
                            ->schema([
                                TextEntry::make('material.name')
                                    ->label('Назва матеріалу'),
                                TextEntry::make('required_quantity')
                                    ->label('Потрібна кількість')
                                    ->numeric(),
                                TextEntry::make('available_quantity')
                                    ->label('Доступна кількість')
                                    ->numeric(),
                            ])
                            ->columns(2)
                            ->placeholder('-')
                            ->columnSpanFull(), // Add red color to the section
                    ]),
                Section::make('Основна інформація')
                    ->columns(2)
                    ->schema([
                        // Основна інформація
                        TextEntry::make('name')
                            ->label('Назва'),
                        TextEntry::make('description')
                            ->label('Опис')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('cost_price')
                            ->label('Собівартість')
                            ->numeric(),
                        TextEntry::make('total_cost')
                            ->label('Загальна вартість')
                            ->numeric(),
                    ]),

                    // Section::make('Ідентифікатори')
                    //     ->columns(2)
                    //     ->schema([
                    //         // Ідентифікатори
                    //         TextEntry::make('order_id')
                    //             ->label('ID Замовлення')
                    //             ->numeric()
                    //             ->placeholder('-'),
                    //         TextEntry::make('product_id')
                    //             ->label('ID Продукту')
                    //             ->numeric()
                    //             ->placeholder('-'),
                    //     ]),

                    Section::make('Статус та шаблон')
                        ->headerActions([
                          Action::make('edit')
                              ->label('Змінити статус')
                              ->icon('heroicon-o-pencil')
                              ->form([
                                  Select::make('status')
                                      ->label('Статус')
                                      ->options([
                                          'Очікується' => 'Очікується',
                                          'в роботі' => 'В роботі',
                                          'виготовлено' => 'Виготовлено',
                                          'скасовано' => 'Скасовано',
                                      ])
                                      ->required(),
                                  Checkbox::make('is_template')
                                      ->label('Шаблон'),
                                    TextInput::make('mark_up')
                                          ->label('Націнка у гривнях')
                                          ->numeric(),
                              ])
                              ->action(function (array $data, \Filament\Resources\Pages\Page $livewire): void {
                                  $record = $livewire->record;
                                  $record->update($data);
                                    Notification::make()
                                        ->title('Статус виробництва оновлено.')
                                        ->success()
                                        ->send();
                              }),
                        ])
                        ->columns(2)
                        ->schema([
                            // Статус та шаблон
                            IconEntry::make('is_template')
                                ->label('Шаблон')
                                ->boolean(),
                             TextEntry::make('mark_up')
                                ->label('Націнка у гривнях')
                                ->default('100')
                                ->numeric(),
                            TextEntry::make('status')
                                ->color(fn (string $state): string => match ($state) {
                                    'очікується' => 'info',
                                    'в роботі' => 'warning',
                                    'виготовлено' => 'success',
                                    'скасовано' => 'danger',
                                    default => 'secondary',
                                })
                                ->label('Статус'),
                        ]),

                    // Section::make('Дати')
                    //     ->columns(2)
                    //     ->schema([
                    //                 // Дати
                    //         TextEntry::make('created_at')
                    //             ->label('Створено')
                    //             ->dateTime()
                    //             ->placeholder('-'),
                    //         TextEntry::make('updated_at')
                    //             ->label('Оновлено')
                    //             ->dateTime()
                    //             ->placeholder('-'),
                    //     ]),

                    Section::make('Інформація про замовлення')
                        ->columns(2)
                        ->schema([
                                    // Інформація про замовлення
                            TextEntry::make('order.name')
                                ->label('Назва Замовлення')
                                ->placeholder('-'),

                            // Інформація про продукт
                            TextEntry::make('product.name')
                                ->label('Назва Продукту')
                                ->placeholder('-'),

                                // Інформація про продукт
                            TextEntry::make('product.paid_amount')
                                ->label('Оплачена сума')
                                ->placeholder('-'),
                        ]),

                    Section::make('Інформація про клієнта')
                        ->columns(2)
                        ->schema([
                            // Інформація про клієнта
                            TextEntry::make('client.name')
                                ->label('Ім\'я Клієнта')
                                ->placeholder('-'),
                            TextEntry::make('client.email')
                                ->label('Email Клієнта')
                                ->placeholder('-'),
                            TextEntry::make('client.customer.phone')
                                ->label('Телефон Клієнта')
                                ->placeholder('-'),
                            KeyValueEntry::make('client.customer.measurements')
                                ->columnSpanFull()
                                ->label('Розміри'),
                        ]),


                         Section::make('Етапи виробництва')
                            ->columns(2)
                            ->schema([
                                RepeatableEntry::make('stages')
                                    ->label('Етапи')
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Назва етапу'),
                                        TextEntry::make('description')
                                            ->label('Опис'),
                                        TextEntry::make('cost')
                                            ->label('Вартість')
                                            ->numeric(),
                                        TextEntry::make('assigned_to.name')
                                            ->label('Виконавець')
                                            ->dateTime(),
                                        TextEntry::make('status')
                                            ->label('Статус'),
                                    ])
                                    ->columns(2)
                                    ->placeholder('-')
                                    ->columnSpanFull(),
                            ]),
                            Section::make('Матеріали виробництва')
                                ->columns(2)
                                ->schema([
                                    RepeatableEntry::make('stages')
                                        ->label('Етапи')
                                        ->schema([
                                            TextEntry::make('material.name')
                                                ->label('Назва матеріалу'),
                                            TextEntry::make('quantity')
                                                ->label('Кількість'),
                                            TextEntry::make('unit_cost')
                                                ->label('Ціна за одиницю')
                                                ->numeric(),
                                            TextEntry::make('total_cost')
                                                ->label('Загальна вартість')
                                                ->numeric(),
                                            TextEntry::make('warehouse.name')
                                                ->label('Склад'),
                                            TextEntry::make('status')
                                                ->label('Статус'),

                                        ])
                                        ->columns(2)
                                        ->placeholder('-')
                                        ->columnSpanFull(),
                                ]),
            ]);
    }
}
