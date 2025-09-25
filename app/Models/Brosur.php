<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brosur extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'gambar',
        'status',
        'deskripsi',
        'file',
        'kategori_brosur_id',
        'harga',
        'tag',
        'spesifikasi',
    ];

    protected $casts = [
        'spesifikasi' => 'array',
        'tag' => 'array',
        'harga' => 'integer'
    ];

    /**
     * Get the kategori brosur that owns the brosur.
     */
    public function kategoriBrosur(): BelongsTo
    {
        return $this->belongsTo(KategoriBrosur::class);
    }
}
