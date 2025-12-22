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
