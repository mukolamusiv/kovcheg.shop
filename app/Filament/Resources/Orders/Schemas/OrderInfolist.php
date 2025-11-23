<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Actions\Action;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Інформація про замовлення')
                ->icon(Heroicon::InformationCircle)
                ->aside()
                ->schema([
                     TextEntry::make('status')
                            ->color(
                                fn (string $state): string => match ($state) {
                                   'очікується' => 'info',
                                    'готове' => 'success',
                                    'скасовано' => 'danger',
                                    'в обробці' => 'primary',
                                })
                            //     [

                            // ])
                            ->label('Статус'),
                      TextEntry::make('shipping_method')
                            ->label('Метод доставки'),
                      TextEntry::make('errors')
                            ->default('Відсутні')
                            ->color(
                                fn (string $state): string => match ($state) {
                                   'Відсутні' => 'success',
                                    default => 'danger',
                                })
                            ->label('Проблеми з замовленням'),
                        RepeatableEntry::make('orderItems')
                            ->columnSpanFull()
                            ->label('Виробництва в замовленні')
                            ->table([
                                //    TextColumn::make('product.name')
                                //        ->label('Продукт'),
                                    TableColumn::make('Виробництво'),
                                    TableColumn::make('Кількість'),
                                    TableColumn::make('Статус виробництва'),
                            ])
                            ->schema([
                                TextEntry::make('production.name')
                                    ->label('Виробництво'),
                                TextEntry::make('quantity')
                                    ->label('Кількість'),
                                TextEntry::make('production.status')
                                    ->label('Статус виробництва'),
                                ]),
                            ])

                    // ->collapsible()
                    // ->collapsed(false)
                    ->columns(3)
                    ->footerActions([
                        Action::make('add_item')
                            ->label('Додати елемент')
                            ->icon(Heroicon::Plus)
                            ->form(fn () => \App\Filament\Resources\Orders\Schemas\OrderAddItem::form())
                            ->action(function (array $data) {
                                dd($data);
                                // Handle the addition of the new item to the order
                                // You can implement the logic to save the new item here
                            }),
                    ])
                    ->columnSpanFull(),
                Section::make('Клієнт')
                    ->schema([
                        TextEntry::make('customer.name')
                            ->label('Клієнт')
                            ->placeholder('-'),
                        TextEntry::make('customer.customer.phone')
                            ->label('Телефон')
                            ->placeholder('-'),
                        TextEntry::make('customer.customer.address')
                            ->label('Адреса')
                            ->placeholder('-'),
                        TextEntry::make('customer.customer.city')
                            ->label('Місто')
                            ->placeholder('-'),
                        TextEntry::make('customer.customer.date_of_birth')
                            ->label('Дата народження')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('customer.customer.note')
                            ->label('Примітка')
                            ->placeholder('-'),
                    ])->columns(2),

                Section::make('Фінанси')
                    ->schema([
                        TextEntry::make('total_amount')
                            ->label('Загальна сума')
                            ->numeric(),
                        TextEntry::make('discount_amount')
                            ->label('Сума знижки')
                            ->numeric(),
                        TextEntry::make('paid_amount')
                            ->label('Сплачена сума')
                            ->numeric(),
                        TextEntry::make('due_amount')
                            ->label('Заборгованість')
                            ->numeric(),
                    ]),

                Section::make('Деталі замовлення')
                    ->schema([
                        TextEntry::make('deadline')
                            ->label('Термін')
                            ->date()
                            ->placeholder('-'),
                        TextEntry::make('notes')
                            ->label('Примітки')
                            ->placeholder('-')
                            ->columnSpanFull(),
                        TextEntry::make('status')
                            ->label('Статус'),
                    ]),

                Section::make('Часові мітки')
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
