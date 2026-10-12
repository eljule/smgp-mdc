<?php

declare(strict_types=1);

namespace App\Actions\Core;

use App\Models\CoreModule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetActiveModulesAction
{
    /**
     * Fetch active modules ordered by display order, with optional search and role filtering.
     *
     * @return Collection<int, CoreModule>
     */
    public function handle(?string $search = null, ?string $userRole = null): Collection
    {
        $query = CoreModule::query()
            ->active()
            ->ordered();

        $role = strtolower(trim((string) $userRole));

        if ($role === 'jperez') {
            // 3 módulos autorizados conforme a la captura: Central, ITAM, Helpdesk
            $query->whereIn('code', ['central', 'itam', 'helpdesk']);
        } elseif ($role === 'mgomez') {
            // 2 módulos autorizados conforme a la captura: Central, Helpdesk
            $query->whereIn('code', ['central', 'helpdesk']);
        }

        $term = trim((string) $search);

        if ($term !== '') {
            $operator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';

            $query->where(function ($q) use ($term, $operator): void {
                $q->where('name', $operator, "%{$term}%")
                    ->orWhere('description', $operator, "%{$term}%")
                    ->orWhere('code', $operator, "%{$term}%");
            });
        }

        return $query->get();
    }
}
