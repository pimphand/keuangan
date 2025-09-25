<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBrosur extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama'
    ];

    /**
     * Get the brosurs for the kategori brosur.
     */
    public function brosurs(): HasMany
    {
        return $this->hasMany(Brosur::class);
    }
}
