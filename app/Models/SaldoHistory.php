<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoHistory extends Model
{
    use HasFactory;

    protected $table = 'saldo_history';

    protected $fillable = [
        'user_id',
        'amount',
        'month_year',
        'notes',
        'admin_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the saldo history.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who added the saldo.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Check if user already has saldo added for current month
     */
    public static function hasAddedThisMonth($userId)
    {
        $currentMonth = date('Y-m');
        return self::where('user_id', $userId)
            ->where('month_year', $currentMonth)
            ->exists();
    }

    /**
     * Get saldo history for a specific month
     */
    public static function getForMonth($userId, $monthYear)
    {
        return self::where('user_id', $userId)
            ->where('month_year', $monthYear)
            ->first();
    }
}
