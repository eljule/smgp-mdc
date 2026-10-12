<?php

declare(strict_types=1);

namespace App\Enums;

enum ModuleStatus: string
{
    case Active = 'active';
    case Maintenance = 'maintenance';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Operativo',
            self::Maintenance => 'Mantenimiento',
        };
    }

    public function isMaintenance(): bool
    {
        return $this === self::Maintenance;
    }

    public function badgeVariant(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Maintenance => 'warning',
        };
    }
}
