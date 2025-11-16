<?php

namespace App\Filament\Resources\Productions\Pages;

use App\Filament\Resources\Productions\ProductionResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewProduction extends ViewRecord
{
    protected static string $resource = ProductionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('startProduction')
            //     ->label('Розпочати виробництво')
            //     ->color('success')
            //     ->action(function (): void {
            //         $production = $this->record;

            //         // // Отримуємо всі етапи виробництва
            //         // $stagesCost = $production->stages()->sum('cost');

            //         // // Отримуємо всі матеріали виробництва
            //         // $materialsCost = $production->materials()
            //         //     ->join('materials', 'production_materials.material_id', '=', 'materials.id')
            //         //     ->sum(\DB::raw('production_materials.quantity * materials.cost'));

            //         // $totalCost = $stagesCost + $materialsCost;

            //         Notification::make()
            //             ->title('Розпочаток виробництва.')
            //             ->success()
            //             ->send();
            //     }),

            // Action::make('calculateCost')
            //     ->label('Встановити ціну')
            //     ->color('success')
            //     ->action(function (): void {
            //         $production = $this->record;

            //         // // Отримуємо всі етапи виробництва
            //         // $stagesCost = $production->stages()->sum('cost');

            //         // // Отримуємо всі матеріали виробництва
            //         // $materialsCost = $production->materials()
            //         //     ->join('materials', 'production_materials.material_id', '=', 'materials.id')
            //         //     ->sum(\DB::raw('production_materials.quantity * materials.cost'));

            //         // $totalCost = $stagesCost + $materialsCost;

            //         Notification::make()
            //             ->title('Продажна ціна: грн.')
            //             ->success()
            //             ->send();
            //     }),

            Action::make('calculateCost')
                ->label('Порахувати вартість')
                ->color('info')
                ->action(function (): void {
                    $production = $this->record;
                    $production->calculateCostPrice();

                    // // Отримуємо всі етапи виробництва
                    // $stagesCost = $production->stages()->sum('cost');

                    // // Отримуємо всі матеріали виробництва
                    // $materialsCost = $production->materials()
                    //     ->join('materials', 'production_materials.material_id', '=', 'materials.id')
                    //     ->sum(\DB::raw('production_materials.quantity * materials.cost'));

                    // $totalCost = $stagesCost + $materialsCost;

                    Notification::make()
                        ->title('Загальна вартість виробництва:'. $production->cost_price .' грн.')
                        ->success()
                        ->send();
                }),


            Action::make('addStages')
                ->label('Додати етапи')
                ->color('primary')
                ->modalHeading('Додати етапи до виробництва')
                ->form([
                    TextInput::make('name')
                        ->label('Назва етапу')
                        ->required(),
                    TextInput::make('description')
                        ->label('Опис')
                        ->maxLength(65535),
                    TextInput::make('cost')
                        ->label('Вартість')
                        ->numeric()
                        ->required(),
                    TextInput::make('duration')
                        ->label('Тривалість (у хвилинах)')
                        ->numeric()
                        ->hidden()
                        ->default(0)
                        ->required(),
                    Select::make('status')
                        ->label('Статус')
                        ->options([
                            'pending' => 'Очікується',
                            'in_progress' => 'В процесі',
                            'completed' => 'Завершено',
                        ])
                        ->required(),
                    Select::make('assigned_to')
                        ->label('Призначено')
                        ->preload()
                        ->options(\App\Models\User::whereIn('role', ['employee', 'manager','admin'])->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->nullable(),
                ])
                ->action(function (array $data): void {
                    $production = $this->record;
                    \App\Models\ProductionStage::create([
                        'production_id' => $production->id,
                        'name'          => $data['name'],
                        'description'   => $data['description'],
                        'cost'          => $data['cost'],
                        'duration'      => 20, // тимчасово фіксоване значення
                        'status'        => $data['status'],
                        'assigned_to'   => $data['assigned_to'],
                    ]);

                    Notification::make()
                        ->title('Етапи успішно додані до виробництва.')
                        ->success()
                        ->send();
                }),
            EditAction::make(),
            Action::make('addMaterials')
                ->label('Додати матеріали')
                ->color('success')
                ->modalHeading('Додати матеріали до виробництва')
                ->form([
                    // Select::make('warehouse_id')
                    //      ->label('Склад')
                    //      //->options(\App\Models\Warehouse::all()->pluck('name', 'id')->toArray())
                    //      ->required(),
                    Select::make('material_id')
                        ->label('Матеріал')
                        ->searchable()
                        ->preload()
                        ->options(\App\Models\Material::all()->pluck('name', 'id')->toArray())
                        ->required(),
                    TextInput::make('quantity')
                        ->label('Кількість')
                        ->numeric()
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $production = $this->record;

                    // Перевірка, чи такий матеріал уже є
                    $existing = \App\Models\ProductionMaterial::where('production_id', $production->id)
                        ->where('material_id', $data['material_id'])
                        // ->where('warehouse_id', $data['warehouse_id'])
                        ->first();

                    if ($existing) {
                        // Якщо є — просто збільшуємо кількість
                        $existing->quantity += $data['quantity'];
                        $existing->save();
                    } else {
                        // Якщо немає — створюємо новий запис
                        \App\Models\ProductionMaterial::create([
                            'production_id' => $production->id,
                            // 'warehouse_id'  => $data['warehouse_id'],
                            'material_id'   => $data['material_id'],
                            'quantity'      => $data['quantity'],
                        ]);
                    }

                    Notification::make()
                        ->title('Матеріали успішно додані до виробництва.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
