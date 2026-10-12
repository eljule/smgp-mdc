<?php

declare(strict_types=1);

namespace App\Actions\Itam;

use App\Models\Itam\Supply;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetSuppliesCatalogAction
{
    public function handle(string $search = '', string $category = 'TODAS', int $perPage = 10): LengthAwarePaginator
    {
        $query = Supply::query();

        if ($category !== 'TODAS') {
            $query->where('category', $category);
        }

        $trimmedSearch = mb_strtolower(trim($search));
        if ($trimmedSearch !== '') {
            $query->where(function ($q) use ($trimmedSearch) {
                $q->whereRaw('LOWER(code) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(name) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(location) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(compatible_models) LIKE ?', ["%{$trimmedSearch}%"]);
            });
        }

        return $query->orderBy('name', 'asc')->paginate($perPage);
    }
}
