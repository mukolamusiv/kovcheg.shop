<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Масив $fillable визначає, які поля можна масово заповнювати
    protected $fillable = [
        'order_id', // Ідентифікатор замовлення
        'product_id', // Ідентифікатор продукту (може бути null)
        'production_id', // Ідентифікатор виробництва (може бути null)
        'quantity', // Кількість товарів
        'unit_price', // Ціна за одиницю в копійках
        'total', // Загальна сума в копійках
    ];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Обчислення загальної суми при створенні запису
            $model->total = $model->quantity * $model->unit_price;
        });

        static::updating(function ($model) {
            // Обчислення загальної суми при оновленні запису
            $model->total = $model->quantity * $model->unit_price;
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function production()
    {
        return $this->belongsTo(Production::class);
    }
}
