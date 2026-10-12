<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ModuleAccentColor;
use App\Enums\ModuleStatus;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $icon_url
 * @property string $route
 * @property ModuleAccentColor $accent_color
 * @property ModuleStatus $status
 * @property string|null $status_message
 * @property string|null $wait_time
 * @property int $order
 * @property bool $is_active
 * @property bool $requires_auth
 * @property array{label?: string, route?: string}|null $secondary_action
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class CoreModule extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'core_modules';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'icon_url',
        'route',
        'accent_color',
        'status',
        'status_message',
        'wait_time',
        'order',
        'is_active',
        'requires_auth',
        'secondary_action',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'accent_color' => ModuleAccentColor::class,
            'status' => ModuleStatus::class,
            'order' => 'integer',
            'is_active' => 'boolean',
            'requires_auth' => 'boolean',
            'secondary_action' => 'array',
        ];
    }

    /**
     * Scope a query to only include active modules.
     *
     * @param  Builder<CoreModule>  $query
     * @return Builder<CoreModule>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order modules by their specified display order.
     *
     * @param  Builder<CoreModule>  $query
     * @return Builder<CoreModule>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc');
    }
}
