<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'material_id',
        'quantity',
        'price_per_unit',
        'total_price',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->price_per_unit = $item->material->cost_per_unit;
            $item->total_price = ($item->quantity * 100) * $item->price_per_unit / 100;
        });

    }


    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
