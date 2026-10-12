<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'core_roles';

    protected $fillable = [
        'code',
        'name',
        'description',
        'subsystem_code',
        'is_system',
        'permission_codes',
        'is_active',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'permission_codes' => 'array',
        'is_active' => 'boolean',
    ];
}
