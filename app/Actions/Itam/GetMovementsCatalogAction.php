<?php

declare(strict_types=1);

namespace App\Actions\Itam;

use App\Models\Itam\AssetMovement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetMovementsCatalogAction
{
    public function handle(string $search = '', int $perPage = 10): LengthAwarePaginator
    {
        $query = AssetMovement::with(['asset.category', 'asset.brand', 'asset.model', 'fromPerson', 'toPerson']);

        $trimmedSearch = mb_strtolower(trim($search));
        if ($trimmedSearch !== '') {
            $query->where(function ($q) use ($trimmedSearch) {
                $q->whereRaw('LOWER(acta_number) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(from_office) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(to_office) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(technician_name) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereHas('asset', function ($qa) use ($trimmedSearch) {
                        $qa->whereRaw('LOWER(computer_code) LIKE ?', ["%{$trimmedSearch}%"]);
                    });
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }
}
