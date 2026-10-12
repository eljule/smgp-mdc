<?php

declare(strict_types=1);

namespace App\Actions\Itam;

use App\Models\Itam\AssetLoan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetLoansCatalogAction
{
    public function handle(string $search = '', string $status = 'TODOS', int $perPage = 10): LengthAwarePaginator
    {
        $query = AssetLoan::with(['requestingPerson', 'items.asset.brand', 'items.asset.model']);

        if ($status !== 'TODOS') {
            $query->where('status', $status);
        }

        $trimmedSearch = mb_strtolower(trim($search));
        if ($trimmedSearch !== '') {
            $query->where(function ($q) use ($trimmedSearch) {
                $q->whereRaw('LOWER(loan_code) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(requesting_office) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(borrower_name) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(event_reason) LIKE ?', ["%{$trimmedSearch}%"]);
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }
}
