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
use Filament\Schemas\Components\Flex;
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
                    Action::make('status')
                        ->label('Змінити статус замовлення')
                        ->form([
                            Select::make('status')
                                ->label('Статус замовлення')
                                ->options([
                                    'оформлено' => 'Оформлено',
                                    'виготовлено' => 'Виготовлено',
                                    //'очікує доставки' => 'Очікує доставки',
                                    'готове' => 'Готове',
                                    'скасовано' => 'Скасовано',
                                ])
                                ->default(fn ($record) => $record->status)
                                ->required(),
                        ])
                        ->action(function (array $data, $record) {
                            $record->update([
                                'status' => $data['status'],
                            ]);
                            Notification::make()
                                ->title('Статус замовлення оновлено')
                                ->success()
                                ->send();
                        }),
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
                                'status' => 'оформлено',
                            ]);

                            Notification::make()
                                ->title('Статус замовлення оновлено')
                                ->body('Зімнено статус замовлення на "оформлено" та розпочато виготовлення продукції.')
                                ->success()
                                ->send();
                        }),

                        Action::make('end_production')
                        ->label('Завершити виготовлення')
                        ->hidden(fn ($record) => $record->status != 'оформлено')
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
                                'status' => 'готове',
                            ]);

                            Notification::make()
                                ->title('Статус замовлення оновлено')
                                ->body('Зімнено статус замовлення на "готове" та завершено виготовлення продукції.')
                                ->success()
                                ->send();
                        })
                        //->url(fn ($record) => route('filament.resources.orders.edit', $record)),
                ])
                ->schema([
                     TextEntry::make('status')
                            ->color(
                                fn (string $state): string => match ($state) {
                                    'оформлено' => 'info',
                                    'готове' => 'success',
                                    'скасовано' => 'danger',
                                    'виготовлено' => 'primary',
                                    default => 'secondary',
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
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'створено' => 'info',
                                                'виготовляється' => 'warning',
                                                'виготовлено' => 'success',
                                                'відхилено' => 'danger',
                                                    default => 'primary',
                                            })
                                            ->label('Статус виробництва'),
                                        TextEntry::make('production.total_cost')
                                            ->label('Вартість виробництва'),
                                        ActionGroup::make([
                                            Action::make('start_productions')
                                                ->label('Розпочати виробництво')
                                                //->hidden(fn ($record) => $record->production->status != 'cтворено')
                                                ->visible(fn ($record) => $record->production->status == 'створено')
                                                ->color('success')
                                                ->icon(Heroicon::Play)
                                                ->action(function (array $data, $record) {
                                                    $record->production->startProduction();
                                                    Notification::make()
                                                        ->title('Виробництво розпочато')
                                                        ->success()
                                                        ->send();
                                                }),
                                            Action::make('stop_production')
                                                ->label('Завершити виробництво')
                                                //->hidden(fn ($record) => $record->production->status != 'виготовляється')
                                                ->visible(fn ($record) => $record->production->status == 'виготовляється')
                                                ->color('danger')
                                                ->icon(Heroicon::Stop)
                                                ->action(function (array $data, $record) {
                                                    $record->production->completeProduction();
                                                    Notification::make()
                                                        ->title('Виробництво завершено')
                                                        ->success()
                                                        ->send();
                                                }),
                                                Action::make('view_production')
                                                    ->label('Переглянути виробництво')
                                                    //->hidden(fn ($record) => $record->production->status != 'Очікується' && $record->production->status != 'розробляється')
                                                    ->color('info')
                                                    ->form([
                                                        Flex::make([
                                                            Section::make([
                                                                TextEntry::make('isStockInsufficient')
                                                                    ->default(fn ($record) => implode(', ', $record->production->errors_materials()[0] ?? []))
                                                                    ->badge()
                                                                    ->color('danger')
                                                                    ->label('Проблеми з матеріалами')
                                                            ]),
                                                            Section::make([
                                                                TextEntry::make('production.created_at')
                                                                    ->label('Дата початку')
                                                                    ->date(),
                                                            ])->grow(false),
                                                        ])->from('md')->visible(fn ($record) => $record->production->errors_materials()[1] ?? false),

                                                        Section::make('Деталі виробництва')->schema([
                                                            TextEntry::make('production.name')
                                                                ->badge()
                                                                ->label('Назва виробництва'),
                                                            TextEntry::make('production.description')
                                                                ->badge()
                                                                ->label('Опис виробництва'),
                                                            TextEntry::make('production.mark_up')
                                                                ->badge()
                                                                ->money('UAN')
                                                                ->label('Націнкa'),
                                                            TextEntry::make('production.quantity')
                                                                ->badge()
                                                                ->label('Кількість'),
                                                            TextEntry::make('production.status')
                                                                ->badge()
                                                                 ->color(fn (string $state): string => match ($state) {
                                                                            'створено' => 'gray',
                                                                            'оформлено' => 'info',
                                                                            'виготовляється' => 'warning',
                                                                            'виготовлено' => 'success',
                                                                            'завершено' => 'secondary',
                                                                            'скасовано' => 'danger',
                                                                            default => 'info',
                                                                    })
                                                                ->label('Статус виробництва'),
                                                            TextEntry::make('production.total_cost')
                                                                ->badge()
                                                                ->label('Загальна вартість виробництва'),
                                                            TextInput::make('production.id')
                                                                ->visible(false)
                                                                ->default(fn ($record) => $record->production->id)
                                                                ->label('ID виробництва'),
                                                            Select::make('production.status')
                                                                ->label('Змінити статус виробництва')
                                                                ->options([
                                                                    'створено' => 'Створено',
                                                                    'виготовляється' => 'Виготовляється',
                                                                    'виготовлено' => 'Виготовлено',
                                                                    'відхилено' => 'Відхилено',
                                                                ])
                                                                ->default(fn ($record) => $record->production->status),
                                                            TextInput::make('production.mark_up')
                                                                ->default(fn ($record) => $record->production->mark_up)
                                                                ->numeric()
                                                                // ->money('UAN')
                                                                ->label('Націнкa'),

                                                        ])
                                                        ->columns(2)
                                                        ->headerActions([
                                                            Action::make('delete_production')
                                                                ->label('Видалити виробництво')
                                                                ->color('danger')
                                                                ->icon(Heroicon::Trash)
                                                                ->action(function (array $data, $record) {
                                                                    $production = $record->production;
                                                                    $production->delete();
                                                                    Notification::make()
                                                                        ->title('Виробництво видалено')
                                                                        ->success()
                                                                        ->send();
                                                                }),
                                                        ])

                                                    ])
                                                    ->action(function (array $data, $record) {
                                                        //dd($data, $record);
                                                        $production = $record->production;
                                                        $production->update([
                                                            // 'name' => $data['production']['name'] ?? $production->name,
                                                            // 'description' => $data['production']['description'] ?? $production->description,
                                                             'mark_up' => $data['production']['mark_up'] ?? $production->mark_up,
                                                            // 'quantity' => $data['production']['quantity'] ?? $production->quantity,
                                                            'status' => $data['production']['status'] ?? $production->status,
                                                        ]);

                                                        $production->save();
                                                        Notification::make()
                                                            ->title('Статус виробництва оновлено')
                                                            ->success()
                                                            ->send();
                                                    })
                                                    ->icon(Heroicon::Eye),
                                                    //->url(fn ($record) => route('filament.administration.resources.productions.view', $record->production->id ?? null)),
                                                Action::make('edit_production_materials')
                                                    ->label('Редагувати матеріали виробництва')
                                                    //->hidden(fn ($record) => $record->production->status != 'очікується' && $record->production->status != 'розробляється' && $record->production->status != 'оформлено')
                                                    ->color('primary')
                                                    ->icon(Heroicon::PencilSquare)
                                                    ->form([

                                                        Repeater::make('production_materials')
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
                                                                    ->numeric()
                                                                    ->step(0.01),
                                                                ])
                                                        ])->fillForm(fn ($record) => [
                                                            'production_materials' => $record->production->materials,
                                                    ])
                                                    ->action(function (array $data, $record) {
                                                        //dd($data, $record->production->materials);
                                                        //dd($data, $record);
                                                        $production = $record->production;
                                                        $production->update([
                                                            'name' => $data['production_materials']['name'] ?? $production->name,
                                                            'description' => $data['production_materials']['description'] ?? $production->description,
                                                            'mark_up' => $data['production_materials']['mark_up'] ?? $production->mark_up,
                                                            //'quantity' => $data['production_materials']['quantity'] ?? $production->quantity,
                                                        ]);
                                                        if (isset($data['production_materials'])) {
                                                            $production->materials()->delete();
                                                            $production->materials()->createMany(
                                                                collect($data['production_materials'] ?? [])
                                                                    ->map(fn ($materialData) => [
                                                                        'material_id' => $materialData['material_id'],
                                                                        'quantity' => $materialData['quantity'],
                                                                    ])
                                                                    ->toArray()
                                                            );
                                                        }
                                                        Notification::make()
                                                            ->title('Матеріали виробництва оновлено')
                                                            ->success()
                                                            ->send();
                                                    }),
                                                Action::make('edit_production_stages')
                                                    ->label('Редагувати етапи виробництва')
                                                    //->hidden(fn ($record) => $record->production->status != 'очікується' && $record->production->status != 'розробляється' && $record->production->status != 'оформлено')
                                                    ->color('secondary')
                                                    ->icon(Heroicon::PencilSquare)
                                                    ->form([

                                                        Repeater::make('production_stages')
                                                            ->label('Етапи виробництва')
                                                            ->schema([
                                                                TextInput::make('name')
                                                                    ->label('Назва етапу')
                                                                    ->required(),
                                                                TextInput::make('description')
                                                                    ->label('Опис етапу')
                                                                    ->nullable(),
                                                                TextInput::make('cost')
                                                                    ->label('Вартість етапу')
                                                                    ->required(),

                                                                Select::make('assigned_to')
                                                                    ->label('Відповідальний користувач')
                                                                    ->searchable()
                                                                    ->options(function () {
                                                                        return \App\Models\User::all()->pluck('name', 'id')->where('role', 'manager')->toArray();
                                                                    }),
                                                                ])
                                                        ])->fillForm(fn ($record) => [
                                                            'production_stages' => $record->production->stages,
                                                    ])
                                                    ->action(function (array $data, $record) {
                                                        //dd($data, $record->production->materials);
                                                        //dd($data, $record);
                                                        $production = $record->production;
                                                        $production->update([
                                                            'name' => $data['production_stages']['name'] ?? $production->name,
                                                            'description' => $data['production_stages']['description'] ?? $production->description,
                                                            'mark_up' => $data['production_stages']['mark_up'] ?? $production->mark_up,
                                                            'quantity' => $data['production_stages']['quantity'] ?? $production->quantity,
                                                        ]);
                                                        if (isset($data['production_stages'])) {
                                                            $production->stages()->delete();
                                                            $production->stages()->createMany(
                                                                collect($data['production_stages'] ?? [])
                                                                    ->map(fn ($stageData) => [
                                                                        'name' => $stageData['name'],
                                                                        'description' => $stageData['description'] ?? null,
                                                                        'cost' => $stageData['cost'],
                                                                        'assigned_to' => $stageData['assigned_to'] ?? null,
                                                                    ])
                                                                    ->toArray()
                                                            );
                                                        }
                                                        Notification::make()
                                                            ->title('Матеріали виробництва оновлено')
                                                            ->success()
                                                            ->send();

                                                        // });

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
                                $productionTemplate = Production::find($data['production']['template_id'] ?? null);

                                $production = Production::create([
                                    'name' => $data['production']['name'],
                                    'description' => $data['production']['description'] ?? null,
                                    'is_template' => false,
                                    'mark_up' => $productionTemplate->mark_up ?? 100,
                                    //'quantity' => $data['production']['quantity'] ?? 1,
                                    'product_id' => $productionTemplate->product_id ?? null,
                                    'order_id' => $record->id,
                                    'status' => 'створено',
                                ]);
                               // dd($data);

                                $production->loadTemplateData($data['production']['template_id'] ?? null);

                               // dd($production);
                                // $production->materials()->createMany(
                                //     collect($productionTemplate->materials ?? [])
                                //         ->map(fn ($material) => [
                                //             'material_id' => $material->material_id,
                                //             'quantity' => $material->quantity,
                                //         ])
                                //         ->toArray()
                                // );

                                // $production->stages()->createMany(
                                //     collect($productionTemplate->stages ?? [])
                                //         ->map(fn ($stage) => [
                                //             'name' => $stage->name,
                                //             'description' => $stage->description,
                                //             'cost' => $stage->cost,
                                //             'assigned_to' => $stage->assigned_to,
                                //         ])
                                //         ->toArray()
                                // );


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
                                //dd($data);
                                // Handle the addition of the new item to the order
                                // You can implement the logic to save the new item here
                            }),

                    ])
                    ->columnSpanFull(),
                Section::make('Клієнт')
                    ->headerActions([
                        Action::make('view_customer')
                            ->label('Переглянути клієнта')
                            ->color('primary')
                            ->icon(Heroicon::Eye)
                            ->url(fn ($record) => route('filament.administration.resources.custumers.view', $record->customer->id ?? null)),

                    ])
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
