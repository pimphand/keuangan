<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'logo',
        'type',
        'industri',
        'maps'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Accessor untuk logo URL
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    /**
     * Get the projects for the client.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
