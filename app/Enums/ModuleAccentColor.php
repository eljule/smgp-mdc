<?php

declare(strict_types=1);

namespace App\Enums;

enum ModuleAccentColor: string
{
    case Emerald = 'emerald';
    case Amber = 'amber';
    case Blue = 'blue';
    case Purple = 'purple';

    public function label(): string
    {
        return match ($this) {
            self::Emerald => 'Verde Castilla',
            self::Amber => 'Naranja / Ámbar',
            self::Blue => 'Azul ITAM',
            self::Purple => 'Morado Helpdesk',
        };
    }

    public function topBorderClass(): string
    {
        return match ($this) {
            self::Emerald => 'border-t-emerald-600',
            self::Amber => 'border-t-amber-500',
            self::Blue => 'border-t-blue-600',
            self::Purple => 'border-t-purple-600',
        };
    }

    public function iconContainerClass(): string
    {
        return match ($this) {
            self::Emerald => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50',
            self::Amber => 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-100 dark:border-amber-900/50',
            self::Blue => 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50',
            self::Purple => 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400 border border-purple-100 dark:border-purple-900/50',
        };
    }

    public function primaryButtonClass(): string
    {
        return match ($this) {
            self::Emerald => 'bg-emerald-700 hover:bg-emerald-800 text-white',
            self::Amber => 'bg-amber-600 hover:bg-amber-700 text-white',
            self::Blue => 'bg-emerald-700 hover:bg-emerald-800 text-white',
            self::Purple => 'bg-emerald-700 hover:bg-emerald-800 text-white',
        };
    }

    public function secondaryButtonClass(): string
    {
        return match ($this) {
            self::Purple => 'border border-purple-200 bg-purple-50 text-purple-700 hover:bg-purple-100 dark:border-purple-800 dark:bg-purple-950/50 dark:text-purple-300 dark:hover:bg-purple-900/50',
            default => 'border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200',
        };
    }
}
