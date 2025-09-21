<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'photo',
        'role',
        'level',
        'saldo',
        'kasbon',
        'kasbon_terpakai',
        'status',
        'bank',
        'rekening',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'saldo' => 'decimal:2',
        'kasbon' => 'decimal:2',
        'kasbon_terpakai' => 'decimal:2',
        'rekening' => 'string',
        'bank' => 'string',
    ];

    public function kasbons()
    {
        return $this->hasMany(Kasbon::class);
    }

    public function approvedKasbons()
    {
        return $this->hasMany(Kasbon::class, 'disetujui_id');
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    /**
     * Assign role to user
     */
    public function assignRole(Role $role): void
    {
        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Remove role from user
     */
    public function removeRole(Role $role): void
    {
        $this->roles()->detach($role->id);
    }

    /**
     * Sync user roles
     */
    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }

    /**
     * Get the saldo history for the user.
     */
    public function saldoHistory()
    {
        return $this->hasMany(SaldoHistory::class);
    }

    /**
     * Check if user can receive saldo addition this month
     */
    public function canReceiveSaldoThisMonth(): bool
    {
        return !SaldoHistory::hasAddedThisMonth($this->id);
    }

    /**
     * Get saldo history for current month
     */
    public function getCurrentMonthSaldoHistory()
    {
        $currentMonth = date('Y-m');
        return $this->saldoHistory()->where('month_year', $currentMonth)->first();
    }
}
