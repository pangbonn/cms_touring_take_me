<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'is_active',
        'avatar',
        'must_change_password',
        'password_changed_at',
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
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'password_changed_at' => 'datetime',
        ];
    }

    /**
     * Get the role that owns the user
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->name === 'superadmin';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * Check if user is report
     */
    public function isReport(): bool
    {
        return $this->role && $this->role->name === 'report';
    }

    /**
     * Check if user has admin privileges (superadmin or admin)
     */
    public function hasAdminPrivileges(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    /**
     * Get user's role name
     */
    public function getRoleName(): string
    {
        return $this->role ? $this->role->name : 'no-role';
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayName(): string
    {
        return $this->role ? $this->role->display_name : 'ไม่มีสิทธิ์';
    }

    /**
     * Get user's full name
     */
    public function getFullName(): string
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        }
        return $this->name;
    }

    /**
     * Get user's avatar URL
     */
    public function getAvatarUrl(): string
    {
        if ($this->avatar) {
            return asset('storage/avatars/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->getFullName()) . '&background=3b82f6&color=ffffff';
    }

    /**
     * Check if user must change password
     */
    public function mustChangePassword(): bool
    {
        return $this->must_change_password;
    }

    /**
     * Mark password as changed
     */
    public function markPasswordAsChanged(): void
    {
        $this->update([
            'must_change_password' => false,
            'password_changed_at' => now(),
        ]);
    }
}
