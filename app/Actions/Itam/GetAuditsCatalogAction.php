<?php

declare(strict_types=1);

namespace App\Actions\Itam;

use App\Models\Itam\InventoryAudit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetAuditsCatalogAction
{
    public function handle(string $search = '', int $perPage = 10): LengthAwarePaginator
    {
        $query = InventoryAudit::with(['verifications.asset']);

        $trimmedSearch = mb_strtolower(trim($search));
        if ($trimmedSearch !== '') {
            $query->where(function ($q) use ($trimmedSearch) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('CAST(year AS TEXT) LIKE ?', ["%{$trimmedSearch}%"]);
            });
        }

        return $query->orderBy('year', 'desc')->paginate($perPage);
    }
}
