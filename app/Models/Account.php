<?php

namespace App\Models;

use App\Traits\HasScaledAttributes;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    // use HasScaledAttributes;

    // protected $scaled = ['balance'];


    protected $fillable = [
        'account_name',
        'account_type',
        'ipn',
        'currency',
        'bank_name',
        'bank_code',
        'address',
        'iban',
        'account_number',
        'balance',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getStatementForPeriod($startDate, $endDate)
    {
        return $this->transactions()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->get();
    }
}
