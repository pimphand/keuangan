<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganKerja extends Model
{
    /** @use HasFactory<\Database\Factories\KunjunganKerjaFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal_kunjungan',
        'client',
        'ringkasan',
        'lokasi',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
