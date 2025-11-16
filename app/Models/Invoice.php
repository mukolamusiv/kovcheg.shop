<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'total_amount',
        'status',
        'supplier_id',
        'items',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $date = now()->format('Ymd');
            $nextId = str_pad(self::max('id') + 1, 4, '0', STR_PAD_LEFT);
            $invoice->invoice_number = 'INV-' . $date . '-' . $nextId;
        });

        static::updated(function ($invoice) {
            $totalAmount = $invoice->calculateTotalAmount();
            $invoice->total_amount = $totalAmount;
            $invoice->save();
        });
    }

    /**
     * Get the supplier associated with the invoice.
     *
     * This method defines an inverse one-to-many relationship
     * between the Invoice model and the User model, where the
     * 'supplier_id' foreign key in the invoices table references
     * the id in the users table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }


    public function calculateTotalAmount()
    {
        return $this->items()->sum('total_price');
    }
}
