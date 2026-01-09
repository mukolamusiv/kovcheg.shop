<?php

namespace App\Models;

use App\Traits\HasScaledAttributes;
use Illuminate\Database\Eloquent\Model;

class ProductionMaterial extends Model
{

    use HasScaledAttributes;

    protected $scaled = [
        'unit_cost',
        'total_cost',
        'quantity',
    ];

    protected $fillable = [
        'production_id',
        'material_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'status',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->unit_cost = $model->material?->cost_per_unit ?? 10;
            $model->total_cost = $model->quantity * $model->unit_cost;
        });

        static::updating(function ($model) {
            $model->unit_cost = $model->material?->cost_per_unit ?? $model->unit_cost;
            //$model->total_cost = $model->quantity * $model->unit_cost;
            $model->total_cost = $model->quantity * $model->unit_cost;
        });

        // Викликається, коли модель отримується з бази даних
        static::retrieved(function ($model) {
            $model->unit_cost = $model->material?->cost_per_unit;
            $model->total_cost = $model->quantity * $model->unit_cost;
            $model->save();
            // Логіка обробки після отримання моделі
        });

        // // Викликається, коли модель видаляється
        // static::deleted(function ($model) {
        //     // Логіка обробки після видалення моделі
        // });

        // // Викликається, коли модель відновлюється (якщо використовується м'яке видалення)
        // static::restored(function ($model) {
        //     // Логіка обробки після відновлення моделі
        // });

        // // Викликається після збереження моделі (створення або оновлення)
        // static::saved(function ($model) {
        //     // Логіка обробки після збереження моделі
        // });

        // // Викликається після оновлення моделі
        // static::updated(function ($model) {
        //     // Логіка обробки після оновлення моделі
        // });

        // // Викликається після створення моделі
        // static::created(function ($model) {
        //     // Логіка обробки після створення моделі
        // });

        // // Викликається перед збереженням моделі (створення або оновлення)
        // static::saving(function ($model) {
        //     // Логіка обробки перед збереженням моделі
        // });

        // // Викликається перед видаленням моделі
        // static::deleting(function ($model) {
        //     // Логіка обробки перед видаленням моделі
        // });

        // // Викликається перед відновленням моделі (якщо використовується м'яке видалення)
        // static::restoring(function ($model) {
        //     // Логіка обробки перед відновленням моделі
        // });
    }

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
