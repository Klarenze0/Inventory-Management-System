<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
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

    // yung user is nala belong sa isang role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // user can have many stockmovemtns
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // user can have many activityLogs
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    // helper methods
    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }
    public function isInventoryManager(): bool
    {
        return $this->role?->name === 'inventory_manager';
    }
    public function isStaff(): bool
    {
        return $this->role?->name === 'staff';
    }
    public function hasRole(string $role): bool
    {
        return $this->role?->name === $role;
    }
}
