<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'name',         // Назва виробництва
        'description',  // Опис виробництва
        'cost_price',   // Собівартість виробництва
        'total_cost',   // Загальна вартість виробництва
        'order_id',     // Ідентифікатор замовлення
        'product_id',   // Ідентифікатор продукту
        'is_template',  // Чи є виробництво шаблоном
        'status',       // Статус виробництва
        'mark_up',      // Нове поле для відсотка націнки
        'quantity',     // Нове поле для кількості виробництва
    ];

   // public $errors_materials = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function client()
    {
        return $this->hasOneThrough(User::class, Order::class, 'id', 'id', 'order_id', 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stages()
    {
        return $this->hasMany(ProductionStage::class);
    }

    public function materials()
    {
        return $this->hasMany(ProductionMaterial::class);
    }

    public function orderItems()
    {
        return $this->hasOne(OrderItem::class);
    }

    public function calculateCostPrice()
    {
        $materialsCost = $this->materials->sum(function ($material) {
            return $material->quantity * $material->unit_cost;
        });

        $stagesCost = $this->stages->sum('cost');

        $this->cost_price = $materialsCost + $stagesCost;
        $this->total_cost = $this->cost_price + $this->mark_up;
        $this->total_cost = $this->total_cost * ($this->orderItems->quantity ?? 1);
        $this->save();

        return  $this->total_cost;
    }


    public function errors_materials()
    {
        $errors = [];
        foreach ($this->materials as $material) {
            if ($material->material->stock_quantity < $material->quantity) {
                $errors[] = "Недостатньо матеріалів на складі: " . $material->material->name;
            }
        }
        $return[0] = $errors;
        if(!empty($errors)) {
            $return[1] = true;
        }

        //$this->errors_materials = $errors;
        return $return;
    }


    public function startProduction()
    {
        $this->calculateCostPrice();
        foreach ($this->materials as $material) {
           if ($material->material->stock_quantity < $material->quantity) {
                throw new \Exception("Недостатньо матеріалів на складі: " . $material->material->name);
                Notification::make()
                    ->title("Недостатньо матеріалів на складі: " . $material->material->name)
                    ->danger()
                    ->send();
            }
           // $material->material->displacements($material->quantity, false); // Зменшуємо кількість матеріалів на складі
        }
        foreach ($this->materials as $material) {
           if ($material->material->stock_quantity < $material->quantity) {
                throw new \Exception("Недостатньо матеріалів на складі: " . $material->material->name);
                Notification::make()
                    ->title("Недостатньо матеріалів на складі: " . $material->material->name)
                    ->danger()
                    ->send();
            }
            $material->status = 'виготовляється';
            $material->material->displacements($material->quantity, false); // Зменшуємо кількість матеріалів на складі
        }
        Notification::make()
            ->title("Виробництво розпочато, матеріали списані зі складу")
            ->success()
            ->send();
        $this->status = 'виготовляється';
        $this->save();
    }

    public function completeProduction()
    {

        // foreach ($this->materials as $material) {
        //     $material->material->displacements($material->quantity, false); // Списуємо матеріали зі складу
        // }
        $this->status = 'виготовлено';
        $this->save();
    }
}
