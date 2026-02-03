<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoventProduct extends Model
{
    protected $fillable = [
        'product_id', // Ідентифікатор продукту
        'production_id', // Ідентифікатор виробництва
        'destination', // Місце призначення
        'order_id', // Ідентифікатор замовлення
        'quantity', // Кількість
        'status', // Статус
        'notes', // Додаткові примітки
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
