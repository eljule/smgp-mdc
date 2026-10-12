<?php

declare(strict_types=1);

namespace App\Actions\Itam;

use App\Models\Itam\Asset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetAssetsCatalogAction
{
    /**
     * Retrieve paginated assets with search and filters.
     */
    public function handle(
        string $search = '',
        string $categoryCode = 'TODAS',
        string $status = 'TODOS',
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Asset::with(['category', 'brand', 'model', 'assignedPerson', 'childAssets']);

        if ($categoryCode !== 'TODAS') {
            $query->whereHas('category', function ($q) use ($categoryCode) {
                $q->where('code', $categoryCode);
            });
        }

        if ($status !== 'TODOS') {
            $query->where('status', $status);
        }

        $trimmedSearch = mb_strtolower(trim($search));
        if ($trimmedSearch !== '') {
            $query->where(function ($q) use ($trimmedSearch) {
                $q->whereRaw('LOWER(computer_code) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(patrimonial_code) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(serial_number) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(office) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereHas('assignedPerson', function ($qp) use ($trimmedSearch) {
                        $qp->whereRaw('LOWER(first_name) LIKE ?', ["%{$trimmedSearch}%"])
                            ->orWhereRaw('LOWER(paternal_surname) LIKE ?', ["%{$trimmedSearch}%"]);
                    })
                    ->orWhereHas('brand', function ($qb) use ($trimmedSearch) {
                        $qb->whereRaw('LOWER(name) LIKE ?', ["%{$trimmedSearch}%"]);
                    })
                    ->orWhereHas('model', function ($qm) use ($trimmedSearch) {
                        $qm->whereRaw('LOWER(name) LIKE ?', ["%{$trimmedSearch}%"]);
                    });
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }
}
