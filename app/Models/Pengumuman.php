<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Pengumuman extends Model
{
    /** @use HasFactory<\Database\Factories\PengumumanFactory> */
    use HasFactory;

    protected $fillable = [
        'judul',
        'isi',
        'gambar',
        'link',
        'status',
        'prioritas',
        'tanggal_mulai',
        'tanggal_selesai',
        'target_role',
        'views_count',
        'user_id',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'target_role' => 'array',
    ];

    // Relationship with User (author)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope for active announcements
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope for recent announcements
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    // Scope for current announcements (within date range)
    public function scopeCurrent($query)
    {
        $now = Carbon::now();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('tanggal_mulai')
                ->orWhere('tanggal_mulai', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('tanggal_selesai')
                ->orWhere('tanggal_selesai', '>=', $now);
        });
    }

    // Scope for priority announcements
    public function scopePriority($query, $priority = 'tinggi')
    {
        return $query->where('prioritas', $priority);
    }

    // Scope for role-targeted announcements
    public function scopeForRole($query, $role)
    {
        return $query->where(function ($q) use ($role) {
            $q->whereNull('target_role')
                ->orWhereJsonContains('target_role', $role);
        });
    }

    // Increment view count
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    // Accessor for formatted date
    public function getTanggalFormattedAttribute()
    {
        return $this->created_at->format('d F Y, H:i');
    }

    // Accessor for excerpt
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->isi), 150);
    }

    // Accessor for priority badge color
    public function getPriorityColorAttribute()
    {
        return match ($this->prioritas) {
            'tinggi' => 'bg-red-100 text-red-800',
            'sedang' => 'bg-yellow-100 text-yellow-800',
            'rendah' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Accessor for priority text
    public function getPriorityTextAttribute()
    {
        return match ($this->prioritas) {
            'tinggi' => 'Prioritas Tinggi',
            'sedang' => 'Prioritas Sedang',
            'rendah' => 'Prioritas Rendah',
            default => 'Normal'
        };
    }

    // Check if announcement is currently active
    public function getIsActiveAttribute()
    {
        $now = Carbon::now();

        if ($this->status !== 'aktif') {
            return false;
        }

        if ($this->tanggal_mulai && $this->tanggal_mulai > $now) {
            return false;
        }

        if ($this->tanggal_selesai && $this->tanggal_selesai < $now) {
            return false;
        }

        return true;
    }
}
