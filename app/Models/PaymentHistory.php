<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentHistory extends Model
{
    protected $fillable = [
        'project_id',
        'amount',
        'payment_date',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'amount' => 'integer',
        'payment_date' => 'date',
    ];

    /**
     * Get the project that owns the payment history.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
