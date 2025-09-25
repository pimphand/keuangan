<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'user_id',
        'client_type',
        'client_name',
        'client_address',
        'client_phone_number',
        'client_nik',
        'client_ktp_name',
        'ktp_photo',
        'notes',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    //boot create
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->po_number = 'PO-' . str_pad(static::max('id') + 1, 5, '0', STR_PAD_LEFT);
            if (Auth::check()) {
                $model->user_id = Auth::user()->id;
            }
        });
    }
}
