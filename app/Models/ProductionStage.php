<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionStage extends Model
{
    // Масив $fillable визначає, які поля можна масово заповнювати
    protected $fillable = [
        'production_id', // ID виробництва, до якого належить етап
        'name', // Назва етапу
        'description', // Опис етапу (може бути порожнім)
        'cost', // Вартість етапу в центах
        'duration', // Тривалість етапу в хвилинах
        'status', // Статус етапу (за замовчуванням "очікується")
        'assigned_to', // ID користувача, відповідального за етап
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
