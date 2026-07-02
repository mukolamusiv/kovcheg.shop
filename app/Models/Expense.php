<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public const CATEGORIES = [
        'оренда' => 'Оренда',
        'комунальні' => 'Комунальні послуги',
        'транспорт' => 'Транспорт',
        'канцтовари' => 'Канцтовари',
        'реклама' => 'Реклама',
        'обладнання' => 'Обладнання та інструменти',
        'зв\'язок' => 'Зв\'язок та інтернет',
        'податки' => 'Податки та збори',
        'харчування' => 'Харчування',
        'інше' => 'Інше',
    ];

    protected $fillable = [
        'category',
        'amount',
        'expense_date',
        'description',
        'notes',
        'status',
        'user_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }
}
