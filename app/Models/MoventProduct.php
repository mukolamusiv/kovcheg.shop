<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoventProduct extends Model
{
    protected $fillable = [
        'product_id',
        'production_id',
        'destination',
        'order_id',
        'quantity',
        'status',
        'notes',
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
