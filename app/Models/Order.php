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
        $total = 0;
        foreach($this->orderItems as $item){
           $total += $item->calculate();
        }
        $this->total_amount = $total;
        $this->save();
        //$this->total_amount = $this->orderItems->sum('total');
        $this->due_amount = $this->total_amount - $this->discount_amount - $this->paid_amount;
        //dd($this, $this->orderItems, $total, $this->total_amount, $this->discount_amount, $this->paid_amount);
        $this->save();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function paid_for()
    {
        //dd($this->transactions()->where('transaction_type', 'витрати')->sum('amount'));
        return $this->transactions()->where('transaction_type', 'надходження')->sum('amount');
    }

    public function payOrder(){
        /**
         * Оновлює властивості моделі замовлення, пов'язані з оплатою,
         * та зберігає зміни в базі даних.
         *
         * - Визначає суму, яка вже була оплачена, за допомогою методу `paid_for()`.
         * - Обчислює суму, що залишилася до оплати, віднімаючи з загальної суми
         *   знижку та вже сплачену суму.
         * - Встановлює статус замовлення:
         *   - 'оплачено', якщо оплачена сума дорівнює або перевищує загальну суму.
         *   - 'частково оплачено', якщо оплачена сума більша за 0, але менша за загальну суму.
         * - Зберігає оновлені дані моделі в базі даних.
         *
         * @return $this Повертає поточний екземпляр моделі замовлення.
         */
        $this->paid_amount = $this->paid_for();
        $this->due_amount = $this->total_amount - $this->discount_amount - $this->paid_amount;
        if($this->paid_amount >= $this->total_amount){
            $this->status = 'оплачено';
        }else if($this->paid_amount > 0){
            $this->status = 'частково оплачено';
        }
        $this->save();
        return $this;
    }
}
