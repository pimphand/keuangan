<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Gajian extends Model
{
    /** @use HasFactory<\Database\Factories\GajianFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'jabatan',
        'gaji_pokok',
        'tunjangan',
        'potongan',
        'gaji_bersih',
        'periode_gaji',
        'tanggal_pembayaran',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'gaji_pokok' => 'integer',
        'tunjangan' => 'integer',
        'potongan' => 'integer',
        'gaji_bersih' => 'integer',
        'tanggal_pembayaran' => 'date',
        'periode_gaji' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedGajiPokokAttribute()
    {
        return 'Rp ' . number_format($this->gaji_pokok, 0, ',', '.');
    }

    public function getFormattedTunjanganAttribute()
    {
        return 'Rp ' . number_format($this->tunjangan, 0, ',', '.');
    }

    public function getFormattedPotonganAttribute()
    {
        return 'Rp ' . number_format($this->potongan, 0, ',', '.');
    }

    public function getFormattedGajiBersihAttribute()
    {
        return 'Rp ' . number_format($this->gaji_bersih, 0, ',', '.');
    }

    public function getFormattedPeriodeGajiAttribute()
    {
        return Carbon::parse($this->periode_gaji)->format('F Y');
    }
}
