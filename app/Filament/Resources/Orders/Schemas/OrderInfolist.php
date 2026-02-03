<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Production;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;

class OrderInfolist
{
    // додати експорт в пдф і ексель
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Інформація про замовлення')
                ->icon(Heroicon::InformationCircle)
                //->aside()
                ->headerActions([
                    Action::make('start_production')
                        ->label('Виготовляти')
                        ->hidden(fn ($record) => $record->status != 'очікується')
                        ->color('info')
                        ->icon(Heroicon::PlayCircle)
                        ->action(function (array $data, $record) {
                            // // Логіка редагування статусу замовлення
                            // dd($data, $record);
                            foreach ($record->orderItems as $item) {
                                $production = $item->production;
                                $production->update(['status' => 'виготовляється']);
                                // if ($production && $production->status == 'Очікується') {

                                // }
                            }
                            $record->update([
                                'status' => 'в обробці',
                            ]);

                            Notification::make()
                                ->title('Статус замовлення оновлено')
                                ->body('Зімнено статус замовлення на "в обробці" та розпочато виготовлення продукції.')
                                ->success()
                                ->send();
                        }),

                        Action::make('end_production')
                        ->label('Завершити виготовлення')
                        ->hidden(fn ($record) => $record->status != 'в обробці')
                        ->color('success')
                        ->icon(Heroicon::StopCircle)
                        ->action(function (array $data, $record) {
                            // // Логіка редагування статусу замовлення
                            // dd($data, $record);
                            foreach ($record->orderItems as $item) {
                                $production = $item->production;
                                $production->update(['status' => 'виготовлено']);
                                // if ($production && $production->status == 'Очікується') {

                                // }
                            }
                            $record->update([
                                'status' => 'в обробці',
                            ]);

                            Notification::make()
                                ->title('Статус замовлення оновлено')
                                ->body('Зімнено статус замовлення на "в обробці" та розпочато виготовлення продукції.')
                                ->success()
                                ->send();
                        })
                        //->url(fn ($record) => route('filament.resources.orders.edit', $record)),
                ])
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
                            ->schema([
                                Fieldset::make('Виробництво')
                                    ->columns([
                                        'default' => 1,
                                        'md' => 2,
                                        'xl' => 5,
                                    ])
                                    ->schema([
                                        TextEntry::make('production.name')
                                            ->label('Назва продукту'),
                                        TextEntry::make('quantity')
                                            ->label('Кількість'),
                                        TextEntry::make('production.status')
                                            ->label('Статус виробництва'),
                                        TextEntry::make('production.total_cost')
                                            ->label('Вартість виробництва'),


                                        ActionGroup::make([
                                            Action::make('start_productions')
                                                ->label('Розпочати виробництво')
                                                ->hidden(fn ($record) => $record->production->status != 'Очікується' && $record->production->status != 'розробляється')
                                                ->color('success')
                                                ->icon(Heroicon::Play)
                                                ->action(function (array $data, $record) {
                                                    //dd($data, $record);
                                                    dd($record->production);
                                                    $production = $record->production;
                                                    $production->update(['status' => 'виготовляється']);

                                                    Notification::make()
                                                        ->title('Виробництво розпочато')
                                                        ->success()
                                                        ->send();
                                                }),
                                            Action::make('stop_production')
                                                ->label('Завершити виробництво')
                                                //->hidden(fn ($record) => $record->production->status != 'Очікується' && $record->production->status != 'розробляється')
                                                ->color('danger')
                                                ->icon(Heroicon::Stop)
                                                ->action(function (array $data, $record) {
                                                    //dd($data, $record);
                                                    //dd($record->production);
                                                    // $production = $record->production;
                                                    // $production->update(['status' => 'виготовляється']);

                                                    // Notification::make()
                                                    //     ->title('Виробництво розпочато')
                                                    //     ->success()
                                                    //     ->send();
                                                }),
                                                Action::make('view_production')
                                                    ->label('Переглянути виробництво')
                                                    //->hidden(fn ($record) => $record->production->status != 'Очікується' && $record->production->status != 'розробляється')
                                                    ->color('info')
                                                    ->icon(Heroicon::Eye)
                                                    ->url(fn ($record) => route('filament.administration.resources.productions.view', $record->production->id ?? null)),
                                            Action::make('edit_production_materials')
                                                    ->label('Редагувати матеріали виробництва')
                                                    //->hidden(fn ($record) => $record->production->status != 'Очікується' && $record->production->status != 'розробляється')
                                                    ->color('primary')
                                                    ->icon(Heroicon::PencilSquare)
                                                    ->form([
                                                        // TextEntry::make('production.name')
                                                        //     ->label('Назва виробництва'),
                                                        // TextEntry::make('production.description')
                                                        //     ->label('Опис виробництва'),
                                                        // TextEntry::make('production.mark_up')
                                                        //     ->label('Націнка виробництва')
                                                        //     ->numeric(),
                                                        // TextEntry::make('production.quantity')
                                                        //     ->label('Кількість виробництва')
                                                        //     ->numeric(),
                                                        Repeater::make('materials')
                                                            ->label('Матеріали для виробництва')
                                                            ->schema([
                                                                Select::make('material_id')
                                                                    ->label('Матеріал')
                                                                    ->searchable()
                                                                    ->options(function () {
                                                                        return \App\Models\Material::all()->pluck('name', 'id')->toArray();
                                                                    }),
                                                                TextInput::make('quantity')
                                                                    ->label('Кількість матеріалу')
                                                                    ->numeric(),
                                                                ])
                                                        ])->fillForm(fn ($record) => [
                                                            'materials' => $record->production->materials,
                                                    ])
                                                    ->action(function (array $data, $record) {
                                                        dd($data, $record->production->materials);
                                                        //dd($data, $record);
                                                        // $production = $record->production;
                                                        // $production->update([
                                                        //     'name' => $data['production']['name'] ?? $production->name,
                                                        //     'description' => $data['production']['description'] ?? $production->description,
                                                        //     'mark_up' => $data['production']['mark_up'] ?? $production->mark_up,
                                                        //     'quantity' => $data['production']['quantity'] ?? $production->quantity,
                                                        // ]);
                                                        // if (isset($data['production']['materials'])) {
                                                        //     $production->materials()->delete();
                                                        //     $production->materials()->createMany(
                                                        //         collect($data['production']['materials'] ?? [])
                                                        //             ->map(fn ($materialData) => [
                                                        //                 'material_id' => $materialData['data']['material_id'],
                                                        //                 'quantity' => $materialData['data']['quantity'],
                                                        //             ])
                                                        //             ->toArray()
                                                        //     );
                                                        // }
                                                    }),
                                            ])->label('Дії')
                                                ->icon('heroicon-m-adjustments-vertical')
                                                //->size(Size::Small)
                                                ->color('info')
                                                ->button(),


                                        ]),


                            ])
                            // ->table([
                            //     //    TextColumn::make('product.name')
                            //     //        ->label('Продукт'),
                            //         TableColumn::make('Виробництво'),
                            //         TableColumn::make('Кількість'),
                            //         TableColumn::make('Статус виробництва'),
                            //         TableColumn::make('Вартість виробництва'),
                            //         TableColumn::make('Дія'),
                            //             // ->formatStateUsing(fn (array $state) => Action::make('view_production')
                            //             //     ->label('Переглянути виробництво')
                            //             //     ->color('primary')
                            //             //     ->icon(Heroicon::Eye)
                            //             //     ->url(route('filament.resources.productions.view', $state['production']['id'] ?? null))
                            //             // ),
                            // ])
                            // ->schema([
                            //     TextEntry::make('production.name')
                            //         ->label('Виробництво'),
                            //     TextEntry::make('quantity')
                            //         ->label('Кількість'),
                            //     TextEntry::make('production.status')
                            //         ->label('Статус виробництва'),
                            //     TextEntry::make('production.total_cost')
                            //         ->label('Вартість виробництва'),
                            //     Html::make('Дія')
                            //         ->content("<a href='/123123123' class='fi-color fi-color-danger fi-bg-color-600 hover:fi-bg-color-500 dark:fi-bg-color-600 dark:hover:fi-bg-color-500 fi-text-color-0 hover:fi-text-color-0 dark:fi-text-color-0 dark:hover:fi-text-color-0 fi-btn fi-size-md fi-ac-btn-action' type='button'>Старт</a>")
                            //     ]),
                            ])

                    // ->collapsible()
                    // ->collapsed(false)
                    ->columns(3)
                    ->footerActions([
                        Action::make('add_production')
                            ->color('success')
                            ->label('Замовити виробництво')
                            ->icon(Heroicon::SquaresPlus)
                            ->form(fn () => \App\Filament\Resources\Orders\Schemas\OrderAddProduction::form())
                            ->action(function (array $data, $record) {
                               // dd($data, $record,$data['production']['materials']);
                                $productionTemplate = Production::find($data['template_id'] ?? null);

                                $production = Production::create([
                                    'name' => $data['production']['name'],
                                    'description' => $data['production']['description'] ?? null,
                                    'is_template' => false,
                                    'mark_up' => $productionTemplate->mark_up ?? 100,
                                    //'quantity' => $data['production']['quantity'] ?? 1,
                                    'product_id' => $productionTemplate->product_id ?? null,
                                    'order_id' => $record->id,
                                ]);

                                $production->materials()->createMany(
                                    collect($data['production']['materials'] ?? [])
                                        ->map(fn ($materialData) => [
                                            'material_id' => $materialData['data']['material_id'],
                                            'quantity' => $materialData['data']['quantity'],
                                        ])
                                        ->toArray()
                                );


                                //$production->calculateCostPrice();

                                $record->orderItems()->create([
                                    'production_id' => $production->id ?? null,
                                    'quantity' => $data['production']['quantity'] ?? 1,
                                    'unit_price' => $production->calculateCostPrice() ?? 10,
                                    'total' => $production->total_cost * ($data['production']['quantity'] ?? 1),
                                ]);



                                Notification::make()
                                    ->title('Виробництво додано до замовлення')
                                    ->success()
                                    ->send();

                            }),
                        Action::make('add_item')
                            ->label('Додати позицію')
                            ->color('success')
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
                        ->headerActions([
                            Action::make('update_financials')
                                ->label('Оновити фінанси')
                                ->color('primary')
                                ->icon(Heroicon::ArrowPath)
                                ->action(function ($record) {
                                    // Логіка оновлення фінансових даних замовлення
                                    $record->calculateTotals();

                                    Notification::make()
                                        ->title('Фінансові дані оновлено')
                                        ->success()
                                        ->send();
                                }),
                        ])
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
