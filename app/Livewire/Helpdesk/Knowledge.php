<?php

declare(strict_types=1);

namespace App\Livewire\Helpdesk;

use App\Actions\Helpdesk\GetKnowledgeArticlesAction;
use App\Models\Helpdesk\KnowledgeArticle;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.subsystem', [
    'title' => 'Base de Conocimientos TI - SIGM Castilla',
    'moduleName' => 'Soporte Técnico y Helpdesk',
    'subsystem' => 'helpdesk',
    'activeTab' => 'kb',
])]
#[Title('Base de Conocimientos TI - SIGM Castilla')]
class Knowledge extends Component
{
    use WithPagination;

    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'categoria')]
    public string $category = 'TODAS';

    public int $perPage = 6;

    public ?int $selectedArticleId = null;

    public bool $showCreateModal = false;

    public string $formTitle = '';

    public string $formCategory = 'HARDWARE';

    public string $formSummary = '';

    public string $formSymptoms = '';

    public string $formSolutionSteps = '';

    public string $formTags = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectArticle(int $id): void
    {
        $this->selectedArticleId = $id;
        $article = KnowledgeArticle::find($id);
        if ($article) {
            $article->increment('views_count');
        }
    }

    public function closeArticle(): void
    {
        $this->selectedArticleId = null;
    }

    public function openCreateModal(): void
    {
        $this->formTitle = '';
        $this->formCategory = 'HARDWARE';
        $this->formSummary = '';
        $this->formSymptoms = '';
        $this->formSolutionSteps = '';
        $this->formTags = '';
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function saveArticle(): void
    {
        $this->validate([
            'formTitle' => 'required|string|max:250',
            'formSummary' => 'required|string',
            'formSolutionSteps' => 'required|string',
        ]);

        $tags = array_filter(array_map('trim', explode(',', $this->formTags)));

        KnowledgeArticle::create([
            'title' => $this->formTitle,
            'category' => $this->formCategory,
            'summary' => $this->formSummary,
            'symptoms' => $this->formSymptoms ?: null,
            'solution_steps' => $this->formSolutionSteps,
            'tags' => array_values($tags),
            'author_technician_name' => 'Soporte ODT',
            'views_count' => 0,
            'helpful_count' => 0,
        ]);

        $this->showCreateModal = false;
    }

    public function render(GetKnowledgeArticlesAction $action): View
    {
        $articles = $action->handle(search: $this->search, category: $this->category, perPage: $this->perPage);
        $selectedArticle = $this->selectedArticleId ? KnowledgeArticle::find($this->selectedArticleId) : null;

        return view('livewire.helpdesk.knowledge', [
            'articles' => $articles,
            'selectedArticle' => $selectedArticle,
        ]);
    }
}
