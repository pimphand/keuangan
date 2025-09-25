<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'brosur_id',
        'status',
        'harga',
        'total_bayar',
        'sisa_bayar',
    ];

    protected $casts = [
        'harga' => 'integer',
        'total_bayar' => 'integer',
        'sisa_bayar' => 'integer',
    ];

    /**
     * Get the client that owns the project.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the brosur that owns the project.
     */
    public function brosur(): BelongsTo
    {
        return $this->belongsTo(Brosur::class);
    }

    /**
     * Get the payment histories for the project.
     */
    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }
}
