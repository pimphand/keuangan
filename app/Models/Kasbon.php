<?php

namespace App\Models;

use App\Transaksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kasbon extends Model
{
    /** @use HasFactory<\Database\Factories\KasbonFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nominal',
        'keterangan',
        'status',
        'disetujui_id',
        'alasan',
        'type_transaksi',
        'bukti',
        'tanggal_pengiriman'
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'disetujui';
    const STATUS_PROCESSING = 'di proses';
    const STATUS_COMPLETED = 'selesai';
    const STATUS_REJECTED = 'ditolak';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function disetujui(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function transaction()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
