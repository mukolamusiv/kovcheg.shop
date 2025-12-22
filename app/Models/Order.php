<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'total_amount',
        'discount_amount',
        'paid_amount',
        'due_amount',
        'deadline',
        'shipping_method',
        'notes',
        'delivery',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }

    public function calculateTotals()
    {
        $this->total_amount = $this->orderItems->sum('total');
        $this->due_amount = $this->total_amount - $this->discount_amount - $this->paid_amount;
        $this->save();
    }
}
