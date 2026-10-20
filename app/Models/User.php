<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'core_users';

    protected $fillable = [
        'username',
        'password',
        'full_name',
        'email',
        'role',
        'allowed_modules',
        'is_active',
        'person_id',
        'must_change_password',
        'password_changed_at',
        'failed_login_attempts',
        'is_locked',
        'locked_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'allowed_modules' => 'array',
        'is_active' => 'boolean',
        'must_change_password' => 'boolean',
        'is_locked' => 'boolean',
        'failed_login_attempts' => 'integer',
        'password_changed_at' => 'datetime',
        'locked_at' => 'datetime',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function getNameAttribute(): string
    {
        return $this->full_name ?: $this->username;
    }

    public function getInitialsAttribute(): string
    {
        $u = strtolower(trim((string) $this->username));
        if ($u === 'root') {
            return 'RO';
        }
        if (str_starts_with($u, 'soporte')) {
            return 'SO';
        }
        if ($u === 'jperez') {
            return 'JP';
        }
        if ($u === 'mgomez') {
            return 'MG';
        }

        if (! empty($this->full_name)) {
            $words = preg_split('/\s+/', trim((string) $this->full_name));
            if ($words && count($words) >= 2) {
                return mb_strtoupper(mb_substr($words[0], 0, 1).mb_substr($words[count($words) - 1], 0, 1));
            }

            return mb_strtoupper(mb_substr((string) $this->full_name, 0, 2));
        }

        return mb_strtoupper(mb_substr((string) $this->username, 0, 2));
    }

    public function getFullNameWithUsernameAttribute(): string
    {
        $name = trim((string) ($this->full_name ?: $this->username));
        $username = trim((string) $this->username);

        if ($username !== '' && strtolower($username) !== strtolower($name)) {
            return "{$name} ({$username})";
        }

        return $name;
    }

    public function getDisplayTitleAttribute(): string
    {
        if (strtolower(trim((string) $this->username)) === 'root') {
            return 'ADMINISTRADOR DEL SISTEMA SIGM';
        }

        return mb_strtoupper($this->full_name ?: $this->username);
    }

    public function getDisplayRoleAttribute(): string
    {
        return $this->role ?: 'Usuario Municipal';
    }

    public function isLocked(): bool
    {
        return (bool) $this->is_locked;
    }

    public function mustChangePassword(): bool
    {
        return (bool) $this->must_change_password;
    }

    /**
     * Record a failed login attempt and lock the account if max attempts are exceeded.
     */
    public function recordFailedLogin(int $maxAttempts = 5): bool
    {
        $this->failed_login_attempts = ($this->failed_login_attempts ?? 0) + 1;

        if ($this->failed_login_attempts >= $maxAttempts) {
            $this->is_locked = true;
            $this->locked_at = now();
            $this->save();

            return true;
        }

        $this->save();

        return false;
    }

    /**
     * Reset the failed login counter.
     */
    public function resetFailedLogins(): void
    {
        if ($this->failed_login_attempts > 0) {
            $this->failed_login_attempts = 0;
            $this->save();
        }
    }

    /**
     * Unlock the user account and reset failed login attempts.
     */
    public function unlock(): void
    {
        $this->is_locked = false;
        $this->locked_at = null;
        $this->failed_login_attempts = 0;
        $this->save();
    }

    /**
     * Mark that the user must change password on their next login.
     */
    public function requirePasswordChange(): void
    {
        $this->must_change_password = true;
        $this->save();
    }

    /**
     * Update password, clear provisional status, and reset failed attempts.
     */
    public function updatePassword(string $newPassword): void
    {
        $this->password = Hash::make($newPassword);
        $this->must_change_password = false;
        $this->password_changed_at = now();
        $this->failed_login_attempts = 0;
        $this->save();
    }

    public function isReportante(): bool
    {
        $roleName = mb_strtolower(trim((string) $this->role));

        return str_contains($roleName, 'reportante') || str_contains($roleName, 'personal general');
    }

    public function canAccessSubsystem(string $subsystem): bool
    {
        if (strtolower(trim((string) $this->username)) === 'root') {
            return true;
        }

        $roleName = mb_strtolower(trim((string) $this->role));
        if ($roleName === 'administrador central') {
            return true;
        }

        // Si es reportante, no accede a paneles técnicos ni administrativos
        if ($this->isReportante()) {
            return false;
        }

        $allowed = (array) ($this->allowed_modules ?? []);
        if (in_array('admin', $allowed, true) && ! in_array('central', $allowed, true)) {
            $allowed[] = 'central';
        }
        if (in_array('central', $allowed, true) && ! in_array('admin', $allowed, true)) {
            $allowed[] = 'admin';
        }

        return in_array($subsystem, $allowed, true);
    }
}
