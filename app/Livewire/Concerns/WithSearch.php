<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Livewire\Attributes\Url;

trait WithSearch
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    public function resetSearch(): void
    {
        $this->search = '';
    }
}
