<?php

declare(strict_types=1);

namespace App\Actions\Helpdesk;

use App\Models\Helpdesk\KnowledgeArticle;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetKnowledgeArticlesAction
{
    public function handle(string $search = '', string $category = 'TODAS', int $perPage = 10): LengthAwarePaginator
    {
        $query = KnowledgeArticle::query();

        if ($category !== 'TODAS') {
            $query->where('category', $category);
        }

        $trimmedSearch = mb_strtolower(trim($search));
        if ($trimmedSearch !== '') {
            $query->where(function ($q) use ($trimmedSearch) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(summary) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(solution_steps) LIKE ?', ["%{$trimmedSearch}%"])
                    ->orWhereRaw('LOWER(author_technician_name) LIKE ?', ["%{$trimmedSearch}%"]);
            });
        }

        return $query->orderBy('views_count', 'desc')->paginate($perPage);
    }
}
