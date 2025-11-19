<?php

namespace App\Models;

use App\Traits\HasScaledAttributes;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{

     use HasScaledAttributes;

    protected $scaled = ['quantity', 'price_per_unit', 'total_price'];

    protected $fillable = [
        'invoice_id',
        'material_id',
        'quantity',
        'price_per_unit',
        'total_price',
        'movent_date',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->price_per_unit = $item->material->cost_per_unit;
            $item->total_price = ($item->quantity * 100) * $item->price_per_unit / 100;
            //dd($item->total_price, $item->quantity, $item->price_per_unit,$item->material);
          //  dd($item->total_price, $item->quantity, $item->price_per_unit);
        });


        // static::updated(function ($item) {
        //     $item->material->displacements($item->quantity, true);
        //     $item->movent_date = now();
        //     $item->save();
        // });

    }


    public function movet()
    {
        $this->material->displacements($this->quantity, true);
        $this->movent_date = now();
        $this->save();

        Notification::make()
            ->title('Матеріал переміщено на склад')
            ->success()
            ->send();
    }

    //     // Mutator: при збереженні від користувача (м → см)
    // public function setQuantityAttribute($value)
    // {
    //     $this->attributes['quantity'] = (int) round($value * 100);
    // }


    // // Accessor: для відображення користувачу (см → м)
    // public function getQuantityAttribute($value)
    // {
    //     return $value / 100; // користувач бачить в метрах
    // }



    //         // Mutator: при збереженні від користувача (м → см)
    // public function setPricePerUnitAttribute($value)
    // {
    //     $this->attributes['price_per_unit'] = (int) round($value * 100);
    // }


    // // Accessor: для відображення користувачу (см → м)
    // public function getPricePerUnitAttribute($value)
    // {
    //     return $value / 100; // користувач бачить в метрах
    // }


    //             // Mutator: при збереженні від користувача (м → см)
    // public function setTotalPriceAttribute($value)
    // {
    //     $this->attributes['total_price'] = (int) round($value * 100);
    // }


    // // Accessor: для відображення користувачу (см → м)
    // public function getTotalPriceAttribute($value)
    // {
    //     return $value / 100; // користувач бачить в метрах
    // }


    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
