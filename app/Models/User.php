<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine whether the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Determine whether the user account is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Determine whether the user is the last remaining active admin.
     *
     * Used to protect the platform from being left without any administrator.
     */
    public function isLastActiveAdmin(): bool
    {
        return $this->isAdmin()
            && self::where('role', 'admin')->where('status', 'active')->count() <= 1;
    }

    /**
     * Get the transactions for the user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Avatar / profile initial helper.
     */
    public function getInitialAttribute(): string
    {
        return strtoupper(substr((string) $this->name, 0, 1));
    }

    /**
     * Role badge color helper.
     */
    public function getRoleClassAttribute(): string
    {
        return $this->isAdmin()
            ? 'bg-[#e8dcc8] text-[#5c3d25] dark:bg-[#6b4423]/30 dark:text-[#d4a574]'
            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
    }

    /**
     * Status badge color helper.
     */
    public function getStatusClassAttribute(): string
    {
        return $this->isActive()
            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300'
            : 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300';
    }
}

