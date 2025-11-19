<?php

namespace App\Models;

use App\Traits\HasScaledAttributes;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    use HasScaledAttributes;

    protected $scaled = ['balance'];


    protected $fillable = [
        'name',
        'balance',
        'account_number',
        'bank_name',
        'currency',
    ];
}
