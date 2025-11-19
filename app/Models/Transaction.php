<?php

namespace App\Models;

use App\Traits\HasScaledAttributes;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{



     use HasScaledAttributes;

    protected $scaled = ['amount'];


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

    protected $casts = [
        'transaction_type' => 'string',
        'transaction_date' => 'datetime',
    ];



     protected static function boot()
    {
        parent::boot();

        static::created(function ($transaction) {
            // Оновлення балансу рахунку при створенні транзакції
            $transaction->updateAccountBalance();
        });
    }

    public function updateAccountBalance()
    {
        $account = $this->account;

        if ($account) {
            if($this->transaction_type === 'витрати') {
                $newBalance = $account->balance - $this->amount;
            } elseif ($this->transaction_type === 'надходження') {
                $newBalance = $account->balance + $this->amount;
            } else {
                return; // Невідомий тип транзакції
            }
            $account->update(['balance' => $newBalance]);

            Notification::make()
                ->title('Баланс рахунку оновлено')
                ->body("Новий баланс рахунку '{$account->name}': " . number_format($newBalance / 100, 2) . " UAN")
                ->success()
                ->send();
        }
    }


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
