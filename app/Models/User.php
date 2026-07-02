<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'commission_percent',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'commission_percent' => 'decimal:2',
        ];
    }

    public function customer()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function managedOrders()
    {
        return $this->hasMany(Order::class, 'manager_id');
    }

    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function managedTransactions()
    {
        return $this->hasMany(Transaction::class, 'manager_id');
    }

    public function supplierInvoices()
    {
        return $this->hasMany(Invoice::class, 'supplier_id');
    }

    public function assignedStages()
    {
        return $this->hasMany(ProductionStage::class, 'assigned_to');
    }

    public static function roleLabels(): array
    {
        return [
            'admin' => 'Адміністратор',
            'manager' => 'Менеджер',
            'employee' => 'Працівник',
            'supplier' => 'Постачальник',
            'customer' => 'Клієнт',
        ];
    }

    public function isManagerRole(): bool
    {
        return in_array($this->role, ['manager', 'admin'], true);
    }

    /**
     * @return array{added_count: int, completed_count: int, total_amount: float}
     */
    public function getManagerOrderStats(string $from, string $to): array
    {
        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();

        $addedQuery = $this->managedOrders()
            ->whereBetween('created_at', [$fromDate, $toDate]);

        $completedCount = $this->managedOrders()
            ->where('status', 'готове')
            ->whereBetween('updated_at', [$fromDate, $toDate])
            ->count();

        return [
            'added_count' => (clone $addedQuery)->count(),
            'completed_count' => $completedCount,
            'total_amount' => (float) (clone $addedQuery)->sum('total_amount'),
        ];
    }
}
