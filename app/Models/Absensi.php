<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    /** @use HasFactory<\Database\Factories\AbsensiFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis',
        'waktu_absen',
        'foto',
        'latitude',
        'longitude',
        'alamat',
        'keterangan',
        'status',
        'terlambat'
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'terlambat' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
