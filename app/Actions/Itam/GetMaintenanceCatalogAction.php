<?php

declare(strict_types=1);

namespace App\Actions\Itam;

use App\Models\Itam\MaintenanceOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetMaintenanceCatalogAction
{
    public function handle(string $search = '', string $status = 'TODOS', int $perPage = 10): LengthAwarePaginator
    {
        $query = MaintenanceOrder::with(['asset.category', 'asset.brand', 'asset.model', 'suppliesUsed.supply']);

        if ($status !== 'TODOS') {
            $query->where('status', $status);
        }

        $trimmedSearch = mb_strtolower(trim($search));
        if ($trimmedSearch !== '') {
            $query->where(function ($q) use ($trimmedSearch) {
                $q->whereRaw('LOWER(code) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(technician_name) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(diagnosis) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereHas('asset', function ($qa) use ($trimmedSearch) {
                        $qa->whereRaw('LOWER(computer_code) LIKE ?', ["%{$trimmedSearch}%"]);
                    });
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }
}
