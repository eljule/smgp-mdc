<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'allowed_modules' => 'array',
        'is_active' => 'boolean',
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
}
