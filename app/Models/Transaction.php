<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'transaction_type',
        'amount',
        'description',
        'transaction_date',
        'user_id',
        'order_id',
        'manager_id',
        'invoice_id',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
