<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'name',
        'description',
        'cost_price',
        'total_cost',
        'order_id',
        'product_id',
        'is_template',
        'status',
        'mark_up',
    ];

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

    public function calculateCostPrice()
    {
        $materialsCost = $this->materials->sum(function ($material) {
            return $material->quantity * $material->unit_cost;
        });

        $stagesCost = $this->stages->sum('cost');

        $this->cost_price = $materialsCost + $stagesCost;
        $this->save();

        return $this->cost_price;
    }
}
