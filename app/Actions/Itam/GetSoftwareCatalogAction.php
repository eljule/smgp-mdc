<?php

declare(strict_types=1);

namespace App\Actions\Itam;

use App\Models\Itam\Software;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetSoftwareCatalogAction
{
    public function handle(string $search = '', int $perPage = 10): LengthAwarePaginator
    {
        $query = Software::withCount('assets');

        $trimmedSearch = mb_strtolower(trim($search));
        if ($trimmedSearch !== '') {
            $query->where(function ($q) use ($trimmedSearch) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(developer) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(license_type) LIKE ?', ["%{$trimmedSearch}%"]);
            });
        }

        return $query->orderBy('name', 'asc')->paginate($perPage);
    }
}
